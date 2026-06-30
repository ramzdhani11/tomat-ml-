from flask import Flask, request, jsonify
import cv2
import numpy as np
import joblib
import os
import logging
import time

logging.basicConfig(level=logging.WARNING)
logger = logging.getLogger(__name__)

app = Flask(__name__)

app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif'}

MODEL_PATH   = "model/modeltomat.pkl"
CLASS_NAMES  = ['matang', 'mentah', 'setengah_matang']
IMG_SIZE     = (256, 256)
BINS         = (8, 8, 8)

model       = None
class_names = CLASS_NAMES.copy()
model_load_error = None   # ← tambahkan ini

CONFIDENCE_THRESHOLD = 0.30


def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS


def load_model():
    global model, class_names, model_load_error
    try:
        if os.path.exists(MODEL_PATH):
            model = joblib.load(MODEL_PATH)
            if hasattr(model, 'classes_'):
                loaded = list(model.classes_)
                if all(isinstance(c, (int, np.integer)) for c in loaded):
                    class_names = CLASS_NAMES.copy()
                else:
                    class_names = [str(c) for c in loaded]
            print(f"✅ Model berhasil dimuat | Kelas: {class_names}")
            return True
        else:
            model_load_error = f"File model tidak ditemukan: {MODEL_PATH}"
            print(f"❌ {model_load_error}")
            return False
    except Exception as e:
        import traceback
        model_load_error = f"{type(e).__name__}: {str(e)}"
        print(f"❌ Gagal memuat model: {e}")
        traceback.print_exc()
        return False

def extract_histogram(image):
    """
    Selaras 100% dengan fungsi extract_histogram() di evaluasi_model.ipynb:
      1. GaussianBlur (5,5) untuk hilangkan noise
      2. Konversi BGR → HSV
      3. Mask saturasi: hanya piksel berwarna (S>=30, V>=30) yang dihitung
      4. calcHist dengan mask untuk tiap channel H, S, V
      5. Normalize + flatten → concat jadi 1 vektor (8+8+8 = 24 fitur)
    """
    image_blur = cv2.GaussianBlur(image, (5, 5), 0)
    image_hsv  = cv2.cvtColor(image_blur, cv2.COLOR_BGR2HSV)

    lower_bound = np.array([0,   30, 30])
    upper_bound = np.array([180, 255, 255])
    mask = cv2.inRange(image_hsv, lower_bound, upper_bound)

    features = []
    for i in range(3):
        hist = cv2.calcHist([image_hsv], [i], mask, [BINS[i]], [0, 256])
        hist = cv2.normalize(hist, hist).flatten()
        features.append(hist)

    return np.concatenate(features)


def preprocess_image_from_bytes(file_bytes):
    try:
        image = cv2.imdecode(file_bytes, cv2.IMREAD_COLOR)
        if image is None:
            logger.error("Tidak dapat decode gambar")
            return None, None

        image    = cv2.resize(image, IMG_SIZE, interpolation=cv2.INTER_LINEAR)
        features = extract_histogram(image)
        return features, image

    except Exception as e:
        logger.error(f"Error preprocessing: {e}")
        return None, None


def is_tomato(image):
    try:
        image = cv2.resize(image, (300, 300))
        blur = cv2.GaussianBlur(image, (7, 7), 0)
        hsv = cv2.cvtColor(blur, cv2.COLOR_BGR2HSV)

        # Mask warna tomat — diperluas
        red1   = cv2.inRange(hsv, np.array([0,   40, 40]), np.array([15, 255, 255]))  # merah
        red2   = cv2.inRange(hsv, np.array([160, 40, 40]), np.array([180, 255, 255])) # merah atas
        orange = cv2.inRange(hsv, np.array([8,   40, 40]), np.array([25, 255, 255]))  # oranye
        yellow = cv2.inRange(hsv, np.array([20,  40, 40]), np.array([40, 255, 255]))  # kuning-oranye ← BARU
        green  = cv2.inRange(hsv, np.array([35,  35, 35]), np.array([85, 255, 255]))  # hijau (mentah)

        mask = cv2.bitwise_or(red1, red2)
        mask = cv2.bitwise_or(mask, orange)
        mask = cv2.bitwise_or(mask, yellow)  # ← tambahkan
        mask = cv2.bitwise_or(mask, green)

        kernel = np.ones((7, 7), np.uint8)
        mask = cv2.morphologyEx(mask, cv2.MORPH_OPEN,  kernel)
        mask = cv2.morphologyEx(mask, cv2.MORPH_CLOSE, kernel)

        contours, _ = cv2.findContours(mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

        if not contours:
            print("[is_tomato] FAILED: tidak ada contour")
            return False

        largest = max(contours, key=cv2.contourArea)
        area = cv2.contourArea(largest)
        total_pixels = 300 * 300
        tomato_ratio = area / total_pixels
        print(f"[is_tomato] tomato_ratio: {tomato_ratio:.3f}")

        if tomato_ratio < 0.05:
            print("[is_tomato] FAILED: objek terlalu kecil / terlalu jauh")
            return False

        perimeter = cv2.arcLength(largest, True)
        if perimeter == 0:
            return False

        circularity = 4 * np.pi * area / (perimeter * perimeter)
        print(f"[is_tomato] circularity: {circularity:.3f}")

        if circularity < 0.40:  # sedikit dilonggarkan 0.45 → 0.40
            print("[is_tomato] FAILED: bentuk tidak cukup bulat")
            return False

        x, y, w, h = cv2.boundingRect(largest)
        aspect_ratio = min(w, h) / max(w, h)
        print(f"[is_tomato] aspect_ratio: {aspect_ratio:.3f}")

        if aspect_ratio < 0.50:  # dilonggarkan 0.55 → 0.50
            print("[is_tomato] FAILED: terlalu lonjong")
            return False

        hull = cv2.convexHull(largest)
        hull_area = cv2.contourArea(hull)
        if hull_area > 0:
            convexity = area / hull_area
            print(f"[is_tomato] convexity: {convexity:.3f}")
            if convexity < 0.65:  # dilonggarkan 0.70 → 0.65
                print("[is_tomato] FAILED: bentuk tidak konveks")
                return False

        contour_mask = np.zeros(mask.shape, dtype=np.uint8)
        cv2.drawContours(contour_mask, [largest], -1, 255, -1)
        color_pixels = cv2.countNonZero(cv2.bitwise_and(mask, contour_mask))
        color_ratio_in_contour = color_pixels / area
        print(f"[is_tomato] color_ratio_in_contour: {color_ratio_in_contour:.3f}")

        if color_ratio_in_contour < 0.35:  # dilonggarkan 0.40 → 0.35
            print("[is_tomato] FAILED: warna tomat tidak dominan di dalam objek")
            return False

        print("[is_tomato] PASSED ✅")
        return True

    except Exception as e:
        print(f"[is_tomato] ERROR: {e}")
        return False


@app.route('/', methods=['GET'])
def home():
    return jsonify({
        'success':              True,
        'message':              '🍅 Tomat Classification API is running!',
        'model_loaded':         model is not None,
        'model_load_error':     model_load_error,
        'classes':              class_names,
        'confidence_threshold': CONFIDENCE_THRESHOLD,
        'endpoints': {
            'health':  'GET /health',
            'predict': 'POST /predict',
            'info':    'GET /info'
        }
    })


@app.route('/health', methods=['GET'])
def health_check():
    return jsonify({
        'success':      True,
        'status':       'healthy',
        'model_loaded': model is not None,
        'service':      'Tomat Classification API'
    })


@app.route('/predict', methods=['POST'])
def predict():
    print("\n" + "=" * 50)
    print("DEBUG: Masuk /predict endpoint")
    start_time = time.time()

    try:
        if model is None:
            return jsonify({
                'success': False,
                'error':   'Model belum dimuat',
                'message': 'Server belum siap'
            }), 500

        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error':   'No file uploaded',
                'message': 'Upload file dengan key "image"'
            }), 400

        file = request.files['image']

        if file.filename == '':
            return jsonify({
                'success': False,
                'error':   'No file selected',
                'message': 'Pilih file gambar terlebih dahulu'
            }), 400

        if not allowed_file(file.filename):
            return jsonify({
                'success': False,
                'error':   'Invalid file type',
                'message': 'Hanya PNG, JPG, JPEG yang diperbolehkan'
            }), 400

        print("STEP: validasi file - PASSED")

        file_bytes = np.frombuffer(file.read(), np.uint8)

        features, image = preprocess_image_from_bytes(file_bytes)

        if features is None or image is None:
            return jsonify({
                'success': False,
                'error':   'Invalid image',
                'message': 'Tidak dapat membaca atau memproses gambar'
            }), 400

        print("STEP: decode & extract features - PASSED")
        print(f"FEATURE SHAPE: {features.shape}")

        print("STEP: is_tomato - RUNNING")
        if not is_tomato(image):
            print("STEP: is_tomato - FAILED")
            return jsonify({
                'success': False,
                'error':   'Bukan Tomat',
                'message': 'Objek ini bukan tomat. Silakan upload gambar tomat (merah, hijau, atau oranye).'
            }), 422

        print("STEP: is_tomato - PASSED")

        print("STEP: prediction - RUNNING")
        X                = features.reshape(1, -1)
        prediction       = model.predict(X)[0]
        prediction_proba = model.predict_proba(X)[0]
        max_confidence   = float(np.max(prediction_proba))

        print(f"PREDICTED: {prediction} | CONFIDENCE: {max_confidence:.4f}")

        if max_confidence < CONFIDENCE_THRESHOLD:
            print(f"STEP: confidence - FAILED ({max_confidence:.4f} < {CONFIDENCE_THRESHOLD})")
            return jsonify({
                'success':    False,
                'error':      'Low confidence',
                'message':    f'Model tidak cukup yakin ({max_confidence*100:.1f}%). Coba foto tomat lebih jelas.',
                'confidence': round(max_confidence * 100, 2)
            }), 422

        if isinstance(prediction, (int, np.integer)):
            predicted_class = class_names[prediction] if prediction < len(class_names) else str(prediction)
        else:
            predicted_class = str(prediction)

        processing_time = time.time() - start_time
        print(f"STEP: prediction - DONE ({processing_time:.3f}s)")

        return jsonify({
            'success': True,
            'prediction': {
                'class': predicted_class,
                'confidence': round(max_confidence, 4),
                'confidence_percentage': round(max_confidence * 100, 2)
            }
        })

    except Exception as e:
        logger.error(f"Error prediksi: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({
            'success': False,
            'error':   'Internal server error',
            'message': str(e)
        }), 500


@app.route('/info', methods=['GET'])
def model_info():
    try:
        if model is None:
            return jsonify({'success': False, 'error': 'Model not loaded'}), 500

        return jsonify({
            'success': True,
            'model_info': {
                'type':              type(model).__name__,
                'classes':           class_names,
                'n_features':        getattr(model, 'n_features_in_', None),
                'n_estimators':      getattr(model, 'n_estimators', None),
                'max_depth':         getattr(model, 'max_depth', None),
                'model_classes_raw': [str(c) for c in model.classes_] if hasattr(model, 'classes_') else None
            },
            'config': {
                'img_size':             IMG_SIZE,
                'bins':                 BINS,
                'confidence_threshold': CONFIDENCE_THRESHOLD,
                'preprocessing':        'GaussianBlur(5,5) + HSV mask(S>=30,V>=30)',
                'model_path':           MODEL_PATH
            }
        })
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)}), 500


@app.errorhandler(413)
def too_large(e):
    return jsonify({'success': False, 'error': 'File too large', 'message': 'Maksimal 16MB'}), 413

@app.errorhandler(404)
def not_found(e):
    return jsonify({'success': False, 'error': 'Endpoint not found'}), 404

@app.errorhandler(500)
def internal_error(e):
    return jsonify({'success': False, 'error': 'Internal server error'}), 500


# Load model di level modul (BUKAN di dalam if __name__ == '__main__')
# supaya tetap jalan baik saat dijalankan lewat gunicorn maupun "python src/app.py".
print("=" * 60)
print("🍅 TOMAT CLASSIFICATION API")
print("=" * 60)

if load_model():
    print(f"✅ Model    : {type(model).__name__}")
    print(f"🎯 Kelas    : {class_names}")
    print(f"📐 IMG_SIZE : {IMG_SIZE}")
    print(f"📊 BINS     : {BINS}")
    print(f"🔒 Threshold: {CONFIDENCE_THRESHOLD} ({CONFIDENCE_THRESHOLD*100:.0f}%)")
else:
    print("❌ Model gagal dimuat! Cek path:", MODEL_PATH)

print("\n📡 Endpoints:")
print("  GET  /        - Info API")
print("  GET  /health  - Health check")
print("  POST /predict - Prediksi kematangan tomat")
print("  GET  /info    - Info model")
print("=" * 60)

if __name__ == '__main__':
    # Hanya dipakai untuk testing lokal (python src/app.py).
    # Di Railway/production, gunicorn yang menjalankan app, baris ini tidak dipakai.
    port = int(os.environ.get("PORT", 5000))
    app.run(
        host="0.0.0.0",
        port=port,
        debug=False
    )