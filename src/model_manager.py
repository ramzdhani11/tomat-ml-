import joblib
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
import os

class ModelManager:
    """
    Kelas untuk mengelola penyimpanan dan pemanggilan model machine learning
    """
    
    def __init__(self, model_path="models"):
        """
        Inisialisasi ModelManager
        
        Parameters:
        model_path: folder untuk menyimpan model
        """
        self.model_path = model_path
        self.model = None
        self.label_encoder = None
        
        # Buat folder jika belum ada
        if not os.path.exists(model_path):
            os.makedirs(model_path)
            print(f"Folder '{model_path}' dibuat")
    
    def save_model(self, model, label_encoder, model_name="tomat_classifier"):
        """
        Menyimpan model dan label encoder ke file .pkl
        
        Parameters:
        model: model machine learning yang sudah trained
        label_encoder: label encoder untuk kelas
        model_name: nama file model
        """
        # Path lengkap untuk file model
        model_file = os.path.join(self.model_path, f"{model_name}.pkl")
        encoder_file = os.path.join(self.model_path, f"{model_name}_encoder.pkl")
        
        try:
            # Simpan model
            joblib.dump(model, model_file)
            print(f"Model berhasil disimpan: {model_file}")
            
            # Simpan label encoder
            joblib.dump(label_encoder, encoder_file)
            print(f"Label encoder berhasil disimpan: {encoder_file}")
            
            # Simpan metadata
            metadata = {
                'model_name': model_name,
                'model_type': type(model).__name__,
                'classes': label_encoder.classes_.tolist(),
                'n_features': model.n_features_in_ if hasattr(model, 'n_features_in_') else None
            }
            
            metadata_file = os.path.join(self.model_path, f"{model_name}_metadata.pkl")
            joblib.dump(metadata, metadata_file)
            print(f"Metadata berhasil disimpan: {metadata_file}")
            
            return True
            
        except Exception as e:
            print(f"Error menyimpan model: {e}")
            return False
    
    def load_model(self, model_name="tomat_classifier"):
        """
        Memuat model dan label encoder dari file .pkl
        
        Parameters:
        model_name: nama file model
        
        Returns:
        tuple: (model, label_encoder) atau (None, None) jika gagal
        """
        # Path lengkap untuk file model
        model_file = os.path.join(self.model_path, f"{model_name}.pkl")
        encoder_file = os.path.join(self.model_path, f"{model_name}_encoder.pkl")
        
        try:
            # Muat model
            self.model = joblib.load(model_file)
            print(f"Model berhasil dimuat: {model_file}")
            
            # Muat label encoder
            self.label_encoder = joblib.load(encoder_file)
            print(f"Label encoder berhasil dimuat: {encoder_file}")
            
            # Tampilkan informasi model
            self.print_model_info(model_name)
            
            return self.model, self.label_encoder
            
        except FileNotFoundError:
            print(f"Error: File model tidak ditemukan: {model_file}")
            return None, None
        except Exception as e:
            print(f"Error memuat model: {e}")
            return None, None
    
    def print_model_info(self, model_name):
        """
        Menampilkan informasi model yang dimuat
        """
        if self.model is not None and self.label_encoder is not None:
            print(f"\n{'='*50}")
            print("INFORMASI MODEL")
            print(f"{'='*50}")
            print(f"Nama Model: {model_name}")
            print(f"Tipe Model: {type(self.model).__name__}")
            print(f"Kelas: {list(self.label_encoder.classes_)}")
            
            if hasattr(self.model, 'n_features_in_'):
                print(f"Jumlah Fitur: {self.model.n_features_in_}")
            
            if hasattr(self.model, 'n_estimators'):
                print(f"Jumlah Estimators: {self.model.n_estimators}")
            
            print(f"{'='*50}")
    
    def predict(self, features):
        """
        Melakukan prediksi menggunakan model yang dimuat
        
        Parameters:
        features: array fitur untuk prediksi
        
        Returns:
        prediksi: hasil prediksi (label kelas)
        prediksi_proba: probabilitas prediksi (jika ada)
        """
        if self.model is None or self.label_encoder is None:
            print("Error: Model belum dimuat. Gunakan load_model() terlebih dahulu")
            return None, None
        
        try:
            # Prediksi
            predictions = self.model.predict(features)
            
            # Konversi ke label kelas
            class_predictions = self.label_encoder.inverse_transform(predictions)
            
            # Probabilitas (jika model mendukung)
            if hasattr(self.model, 'predict_proba'):
                probabilities = self.model.predict_proba(features)
                return class_predictions, probabilities
            else:
                return class_predictions, None
                
        except Exception as e:
            print(f"Error saat prediksi: {e}")
            return None, None
    
    def predict_single(self, feature):
        """
        Melakukan prediksi untuk satu sampel
        
        Parameters:
        feature: array fitur tunggal
        
        Returns:
        prediksi: hasil prediksi (label kelas)
        """
        # Reshape untuk single sample
        feature = np.array(feature).reshape(1, -1)
        
        predictions, _ = self.predict(feature)
        
        if predictions is not None:
            return predictions[0]
        else:
            return None

def example_usage():
    """
    Contoh penggunaan ModelManager
    """
    print("CONTOH PENGGUNAAN MODEL MANAGER")
    print("="*50)
    
    # 1. Buat model dummy
    print("\n1. Membuat dan training model...")
    model = RandomForestClassifier(n_estimators=10, random_state=42)
    
    # Data dummy
    X_train = np.random.rand(100, 24)  # 24 fitur (8 bin x 3 channel RGB)
    y_train = np.random.choice([0, 1, 2], 100)  # 3 kelas
    
    # Training
    model.fit(X_train, y_train)
    
    # Label encoder
    label_encoder = LabelEncoder()
    label_encoder.fit(['matang', 'mentah', 'setengah_matang'])
    
    # 2. Simpan model
    print("\n2. Menyimpan model...")
    manager = ModelManager("models")
    success = manager.save_model(model, label_encoder, "tomat_rf_model")
    
    if success:
        print("Model berhasil disimpan!")
    
    # 3. Muat model
    print("\n3. Memuat model...")
    loaded_model, loaded_encoder = manager.load_model("tomat_rf_model")
    
    if loaded_model is not None:
        print("Model berhasil dimuat!")
        
        # 4. Test prediksi
        print("\n4. Melakukan prediksi...")
        
        # Data test
        X_test = np.random.rand(5, 24)
        
        # Prediksi
        predictions, probabilities = manager.predict(X_test)
        
        if predictions is not None:
            print("Hasil prediksi:")
            for i, (pred, prob) in enumerate(zip(predictions, probabilities)):
                print(f"Sampel {i+1}: {pred}")
                print(f"  Probabilitas: matang={prob[0]:.3f}, mentah={prob[1]:.3f}, setengah_matang={prob[2]:.3f}")
        
        # 5. Prediksi single
        print("\n5. Prediksi single sample...")
        single_feature = np.random.rand(24)
        single_pred = manager.predict_single(single_feature)
        print(f"Hasil prediksi single: {single_pred}")

if __name__ == "__main__":
    example_usage()
