from flask import Flask, request, jsonify
import cv2
import numpy as np
import joblib
import os
import logging
import time

# Konfigurasi logging - hanya WARNING ke atas agar tidak lambat
logging.basicConfig(level=logging.WARNING)
logger = logging.getLogger(__name__)

app = Flask(__name__)

# Konfigurasi
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB max file size
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'gif'}

# Path ke model
MODEL_PATH = "model_tomat.pkl"

# Global variables untuk model (di-load sekali saja)
model = None
class_names = ['matang', 'mentah', 'setengah_matang']

def allowed_file(filename):
    """Check if file has allowed extension"""
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def load_model():
    """Load model sekali saja saat startup"""
    global model
    
    try:
        if os.path.exists(MODEL_PATH):
            model = joblib.load(MODEL_PATH)
            print("✅ Model berhasil dimuat")
            return True
        else:
            print(f"❌ ERROR: File model tidak ditemukan: {MODEL_PATH}")
            return False
            
    except Exception as e:
        print(f"❌ ERROR: Gagal memuat model: {e}")
        return False

def extract_color_histogram(image, bins=(8, 8, 8)):
    """
    Ekstraksi fitur Color Histogram HSV dari gambar
    Langsung dari array BGR tanpa konversi ulang yang tidak perlu
    """
    try:
        # Konversi dari BGR ke HSV
        image_hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
        
        # Hitung dan normalisasi histogram untuk setiap channel HSV
        features = []
        for i in range(3):
            hist = cv2.calcHist([image_hsv], [i], None, [bins[i]], [0, 256])
            hist = cv2.normalize(hist, hist).flatten()
            features.append(hist)
        
        return np.concatenate(features)
    except Exception as e:
        logger.error(f"Error ekstraksi fitur: {e}")
        return None

def preprocess_image_from_memory(file_stream):
    """
    Preprocessing gambar dari memory buffer.
    Laravel sudah kirim gambar 256x256, cukup decode + ekstrak fitur saja.
    """
    try:
        # Baca file stream ke memory sekaligus
        file_bytes = np.frombuffer(file_stream.read(), np.uint8)
        
        # Decode gambar
        image = cv2.imdecode(file_bytes, cv2.IMREAD_COLOR)
        
        if image is None:
            logger.error("Tidak dapat decode gambar dari memory")
            return None
        
        # Resize hanya jika gambar BUKAN 256x256
        # (jika Laravel sudah resize, skip langkah ini)
        h, w = image.shape[:2]
        if h != 256 or w != 256:
            image = cv2.resize(image, (256, 256), interpolation=cv2.INTER_LINEAR)
        
        # Ekstraksi fitur histogram HSV
        return extract_color_histogram(image)
        
    except Exception as e:
        logger.error(f"Error preprocessing from memory: {e}")
        return None

@app.route('/', methods=['GET'])
def home():
    """Home endpoint"""
    return jsonify({
        'success': True,
        'message': '🍅 Tomat Classification API is running!',
        'endpoints': {
            'health': '/health',
            'predict': '/predict (POST)',
            'info': '/info'
        },
        'model_loaded': model is not None,
        'service': 'Tomat Classification API v1.0.0',
        'classes': class_names
    })

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'success': True,
        'status': 'healthy',
        'model_loaded': model is not None,
        'service': 'Tomat Classification API'
    })

@app.route('/predict', methods=['POST'])
def predict():
    """
    Endpoint prediksi kematangan tomat - Full memory processing, no disk I/O
    Expected: multipart/form-data dengan file 'image'
    """
    start_time = time.time()
    
    try:
        if model is None:
            return jsonify({
                'success': False,
                'error': 'Model belum dimuat',
                'message': 'Server tidak siap untuk prediksi'
            }), 500
        
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No file uploaded',
                'message': 'Harap upload file gambar'
            }), 400
        
        file = request.files['image']
        
        if file.filename == '':
            return jsonify({
                'success': False,
                'error': 'No file selected',
                'message': 'Harap pilih file gambar'
            }), 400
        
        if not allowed_file(file.filename):
            return jsonify({
                'success': False,
                'error': 'Invalid file type',
                'message': 'Hanya file PNG, JPG, JPEG yang diperbolehkan'
            }), 400
        
        # Preprocessing langsung dari memory (tanpa file temporary)
        features = preprocess_image_from_memory(file)
        
        if features is None:
            return jsonify({
                'success': False,
                'error': 'Processing failed',
                'message': 'Gagal memproses gambar dari memory'
            }), 400
        
        # Reshape + prediksi dalam satu langkah
        features_reshaped = features.reshape(1, -1)
        prediction = model.predict(features_reshaped)[0]
        prediction_proba = model.predict_proba(features_reshaped)[0]
        
        predicted_class = class_names[prediction]
        confidence = float(np.max(prediction_proba))
        
        # Format probabilitas
        probabilities = {
            class_names[i]: {
                'probability': float(p),
                'percentage': float(p * 100)
            }
            for i, p in enumerate(prediction_proba)
        }
        
        processing_time = time.time() - start_time
        
        return jsonify({
            'success': True,
            'prediction': {
                'class': predicted_class,
                'confidence': confidence,
                'confidence_percentage': confidence * 100,
                'probabilities': probabilities
            },
            'metadata': {
                'model_type': 'RandomForest',
                'features_used': int(features.shape[0]),
                'preprocessing': 'Memory Processing: Resize 256x256 + HSV Histogram (8x8x8)',
                'processing_time_seconds': round(processing_time, 3),
                'performance': 'Optimized (no temporary files)'
            }
        })
        
    except Exception as e:
        logger.error(f"Error dalam prediksi: {e}")
        return jsonify({
            'success': False,
            'error': 'Internal server error',
            'message': f'Terjadi kesalahan: {str(e)}'
        }), 500

@app.route('/info', methods=['GET'])
def model_info():
    """Endpoint untuk informasi model"""
    try:
        if model is None:
            return jsonify({'success': False, 'error': 'Model not loaded'}), 500
        
        return jsonify({
            'success': True,
            'model_info': {
                'type': type(model).__name__,
                'classes': class_names,
                'n_features': getattr(model, 'n_features_in_', None),
                'n_estimators': getattr(model, 'n_estimators', None)
            },
            'api_info': {
                'version': '1.0.0',
                'supported_formats': ['PNG', 'JPG', 'JPEG'],
                'max_file_size': '16MB',
                'preprocessing': 'Memory Processing: Resize 256x256 + HSV Histogram (8x8x8)',
                'performance': 'Optimized (no temporary files)'
            }
        })
        
    except Exception as e:
        return jsonify({'success': False, 'error': str(e)}), 500

@app.errorhandler(413)
def too_large(e):
    return jsonify({'success': False, 'error': 'File too large', 'message': 'Ukuran file maksimal 16MB'}), 413

@app.errorhandler(404)
def not_found(e):
    return jsonify({
        'success': False,
        'error': 'Endpoint not found',
        'available_endpoints': ['/health', '/predict (POST)', '/info', '/']
    }), 404

@app.errorhandler(500)
def internal_error(e):
    return jsonify({'success': False, 'error': 'Internal server error'}), 500

if __name__ == '__main__':
    print("=" * 60)
    print("🍅 TOMAT CLASSIFICATION API")
    print("=" * 60)
    
    if load_model():
        print("🚀 API siap digunakan")
        print(f"📊 Model: {type(model).__name__}")
        print(f"🎯 Kelas: {class_names}")
    else:
        print("❌ ERROR: Model gagal dimuat!")
        exit(1)
    
    print("\n📡 Endpoints:")
    print("  GET  /        - Home/API Info")
    print("  GET  /health  - Health check")
    print("  POST /predict - Prediksi kematangan tomat")
    print("  GET  /info    - Informasi model")
    print("\n⚡ Processing: In-memory (no temporary files)")
    print("🌐 Starting server on http://127.0.0.1:5000")
    print("=" * 60)
    
    # ✅ FIX UTAMA: debug=False agar tidak ada overhead auto-reload
    # use_reloader=False mencegah model di-load 2x saat startup
    app.run(host='127.0.0.1', port=5000, debug=False, use_reloader=False, threaded=True)