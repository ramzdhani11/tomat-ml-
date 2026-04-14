import cv2
import numpy as np
import joblib
import os

def extract_color_histogram(image, bins=(8, 8, 8)):
    """
    Ekstraksi fitur Color Histogram RGB dari gambar
    """
    # Konversi dari BGR ke RGB
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    
    # Hitung histogram untuk setiap channel
    hist_r = cv2.calcHist([image_rgb], [0], None, [bins[0]], [0, 256])
    hist_g = cv2.calcHist([image_rgb], [1], None, [bins[1]], [0, 256])
    hist_b = cv2.calcHist([image_rgb], [2], None, [bins[2]], [0, 256])
    
    # Normalisasi histogram
    hist_r = cv2.normalize(hist_r, hist_r).flatten()
    hist_g = cv2.normalize(hist_g, hist_g).flatten()
    hist_b = cv2.normalize(hist_b, hist_b).flatten()
    
    # Gabungkan semua histogram
    features = np.concatenate([hist_r, hist_g, hist_b])
    
    return features

def load_model_and_predict():
    """
    Memuat model yang sudah disimpan dan melakukan prediksi
    """
    print("=" * 60)
    print("PREDIKSI KEMATANGAN TOMAT")
    print("Menggunakan Model yang Sudah Disimpan")
    print("=" * 60)
    
    # Path ke model
    models_folder = "models"
    model_file = os.path.join(models_folder, "tomat_classifier.pkl")
    encoder_file = os.path.join(models_folder, "label_encoder.pkl")
    metadata_file = os.path.join(models_folder, "metadata.pkl")
    
    # Cek apakah file model ada
    if not os.path.exists(model_file):
        print(f"Error: File model tidak ditemukan: {model_file}")
        print("Jalankan main.py terlebih dahulu untuk training dan menyimpan model")
        return
    
    try:
        # Muat model
        print("\n1. MEMUAT MODEL")
        print("-" * 40)
        model = joblib.load(model_file)
        label_encoder = joblib.load(encoder_file)
        metadata = joblib.load(metadata_file)
        
        print(f"Model berhasil dimuat: {model_file}")
        print(f"Tipe Model: {metadata['model_type']}")
        print(f"Jumlah Estimators: {metadata['n_estimators']}")
        print(f"Jumlah Fitur: {metadata['n_features']}")
        print(f"Kelas: {metadata['classes']}")
        print(f"Akurasi Training: {metadata['accuracy']:.4f}")
        
        # Muat metadata
        print(f"\nMetadata berhasil dimuat: {metadata_file}")
        
        # Prediksi dengan gambar baru
        print("\n2. PREDIKSI GAMBAR BARU")
        print("-" * 40)
        
        # Contoh: prediksi semua gambar dalam folder
        dataset_path = "."
        classes = [d for d in os.listdir(dataset_path) 
                   if os.path.isdir(os.path.join(dataset_path, d))]
        
        print("Melakukan prediksi pada contoh gambar...")
        
        for class_name in classes:
            class_path = os.path.join(dataset_path, class_name)
            image_files = [f for f in os.listdir(class_path) 
                          if f.lower().endswith(('.png', '.jpg', '.jpeg'))]
            
            if image_files:
                # Ambil gambar pertama sebagai contoh
                sample_image = image_files[0]
                image_path = os.path.join(class_path, sample_image)
                
                try:
                    # Baca dan proses gambar
                    image = cv2.imread(image_path)
                    if image is not None:
                        # Ekstraksi fitur
                        features = extract_color_histogram(image)
                        features = features.reshape(1, -1)  # Reshape untuk single prediction
                        
                        # Prediksi
                        prediction = model.predict(features)[0]
                        prediction_proba = model.predict_proba(features)[0]
                        
                        # Konversi ke label kelas
                        predicted_class = label_encoder.inverse_transform([prediction])[0]
                        
                        # Tampilkan hasil
                        print(f"\nGambar: {sample_image}")
                        print(f"Kelas Aktual: {class_name}")
                        print(f"Prediksi: {predicted_class}")
                        print(f"Probabilitas:")
                        for i, class_name in enumerate(label_encoder.classes_):
                            print(f"  {class_name}: {prediction_proba[i]:.4f} ({prediction_proba[i]*100:.2f}%)")
                        
                        # Benar/Salah
                        is_correct = (class_name == predicted_class)
                        print(f"Status: {'BENAR' if is_correct else 'SALAH'}")
                        
                except Exception as e:
                    print(f"Error memproses {image_path}: {e}")
        
        # Interactive prediction
        print("\n3. PREDIKSI INTERAKTIF")
        print("-" * 40)
        print("Masukkan path gambar untuk prediksi (atau 'exit' untuk keluar):")
        
        while True:
            image_path = input("\nPath gambar: ").strip()
            
            if image_path.lower() == 'exit':
                break
            
            if not os.path.exists(image_path):
                print(f"Error: File tidak ditemukan: {image_path}")
                continue
            
            try:
                # Baca dan proses gambar
                image = cv2.imread(image_path)
                if image is None:
                    print(f"Error: Tidak dapat membaca gambar: {image_path}")
                    continue
                
                # Ekstraksi fitur
                features = extract_color_histogram(image)
                features = features.reshape(1, -1)
                
                # Prediksi
                prediction = model.predict(features)[0]
                prediction_proba = model.predict_proba(features)[0]
                
                # Konversi ke label kelas
                predicted_class = label_encoder.inverse_transform([prediction])[0]
                
                # Tampilkan hasil
                print(f"\nHasil Prediksi:")
                print(f"Kelas: {predicted_class}")
                print(f"Probabilitas:")
                for i, class_name in enumerate(label_encoder.classes_):
                    print(f"  {class_name}: {prediction_proba[i]:.4f} ({prediction_proba[i]*100:.2f}%)")
                
                # Confidence
                max_prob = np.max(prediction_proba)
                print(f"Confidence: {max_prob:.4f} ({max_prob*100:.2f}%)")
                
            except Exception as e:
                print(f"Error: {e}")
        
        print("\n" + "=" * 60)
        print("PREDIKSI SELESAI!")
        print("=" * 60)
        
    except Exception as e:
        print(f"Error memuat model: {e}")

def predict_single_image(image_path):
    """
    Fungsi untuk prediksi satu gambar
    
    Parameters:
    image_path: path ke file gambar
    
    Returns:
    tuple: (predicted_class, probabilities)
    """
    # Muat model
    models_folder = "models"
    model_file = os.path.join(models_folder, "tomat_classifier.pkl")
    encoder_file = os.path.join(models_folder, "label_encoder.pkl")
    
    if not os.path.exists(model_file):
        return None, None
    
    try:
        # Muat model dan encoder
        model = joblib.load(model_file)
        label_encoder = joblib.load(encoder_file)
        
        # Baca gambar
        image = cv2.imread(image_path)
        if image is None:
            return None, None
        
        # Ekstraksi fitur
        features = extract_color_histogram(image)
        features = features.reshape(1, -1)
        
        # Prediksi
        prediction = model.predict(features)[0]
        prediction_proba = model.predict_proba(features)[0]
        
        # Konversi ke label kelas
        predicted_class = label_encoder.inverse_transform([prediction])[0]
        
        return predicted_class, prediction_proba
        
    except Exception as e:
        print(f"Error: {e}")
        return None, None

if __name__ == "__main__":
    load_model_and_predict()

    
