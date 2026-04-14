import cv2
import numpy as np
import os
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report, confusion_matrix
import matplotlib.pyplot as plt
import seaborn as sns
import joblib
from sklearn.preprocessing import LabelEncoder

def extract_color_histogram(image, bins=(8, 8, 8)):
    """
    Ekstraksi fitur Color Histogram HSV dari gambar
    
    Parameters:
    image: gambar dalam format BGR (OpenCV)
    bins: jumlah bin untuk setiap channel HSV
    
    Returns:
    features: array fitur histogram yang telah dinormalisasi
    """
    # Konversi dari BGR ke HSV
    image_hsv = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
    
    # Hitung dan normalisasi histogram untuk setiap channel HSV
    features = []
    for i in range(3):
        hist = cv2.calcHist([image_hsv], [i], None, [bins[i]], [0, 256])
        hist = cv2.normalize(hist, hist).flatten()
        features.append(hist)
    
    # Gabungkan semua histogram
    features = np.concatenate(features)
    
    return features

def plot_confusion_matrix(y_true, y_pred, class_names, save_path='confusion_matrix.png'):
    """
    Menampilkan confusion matrix dalam bentuk heatmap dan menyimpannya sebagai file PNG
    
    Parameters:
    y_true: label aktual
    y_pred: label prediksi
    class_names: list nama kelas
    save_path: path untuk menyimpan file gambar
    """
    # Hitung confusion matrix
    cm = confusion_matrix(y_true, y_pred)
    
    # Buat figure
    plt.figure(figsize=(10, 8))
    
    # Buat heatmap dengan seaborn
    sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', 
                xticklabels=class_names, yticklabels=class_names,
                cbar_kws={'label': 'Jumlah Sampel'},
                square=True,
                linewidths=0.5,
                annot_kws={'size': 12, 'weight': 'bold'})
    
    # Set title dan labels
    plt.title('Confusion Matrix - Klasifikasi Tingkat Kematangan Tomat', 
              fontsize=16, fontweight='bold', pad=20)
    plt.xlabel('Kelas Prediksi', fontsize=12, fontweight='bold')
    plt.ylabel('Kelas Aktual', fontsize=12, fontweight='bold')
    
    # Rotate labels untuk better readability
    plt.xticks(rotation=45, ha='right')
    plt.yticks(rotation=0)
    
    # Add text summary
    total_samples = len(y_true)
    accuracy = np.mean(y_true == y_pred) * 100
    plt.figtext(0.5, 0.02, f'Total Sampel: {total_samples} | Akurasi: {accuracy:.2f}%', 
                ha='center', fontsize=11, style='italic')
    
    # Adjust layout
    plt.tight_layout()
    
    # Save sebagai PNG dengan high quality
    plt.savefig(save_path, dpi=300, bbox_inches='tight', facecolor='white')
    
    # Tampilkan plot
    plt.show()
    
    # Print summary
    print(f"\nConfusion Matrix telah disimpan sebagai: {save_path}")
    print(f"Ukuran gambar: 300 DPI")
    
    return cm

def load_dataset(dataset_path):
    """
    Membaca seluruh gambar dari folder dataset dan melakukan ekstraksi fitur
    
    Parameters:
    dataset_path: path ke folder dataset
    
    Returns:
    features: array fitur dari semua gambar
    labels: array label dari semua gambar
    class_names: nama kelas
    """
    features = []
    labels = []
    class_names = []
    
    # Dapatkan semua folder kelas
    classes = [d for d in os.listdir(dataset_path) 
               if os.path.isdir(os.path.join(dataset_path, d))]
    classes.sort()  # Urutkan untuk konsistensi
    
    print(f"Ditemukan {len(classes)} kelas: {classes}")
    
    for class_idx, class_name in enumerate(classes):
        class_path = os.path.join(dataset_path, class_name)
        class_names.append(class_name)
        
        # Dapatkan semua file gambar dalam folder kelas
        image_files = [f for f in os.listdir(class_path) 
                      if f.lower().endswith(('.png', '.jpg', '.jpeg'))]
        
        print(f"Memproses kelas '{class_name}': {len(image_files)} gambar")
        
        for image_file in image_files:
            image_path = os.path.join(class_path, image_file)
            
            try:
                # Baca gambar
                image = cv2.imread(image_path)
                if image is None:
                    print(f"Warning: Tidak dapat membaca gambar {image_path}")
                    continue
                
                # Ekstraksi fitur
                feature = extract_color_histogram(image)
                features.append(feature)
                labels.append(class_idx)
                
            except Exception as e:
                print(f"Error memproses {image_path}: {e}")
                continue
    
    return np.array(features), np.array(labels), class_names

def main():
    """
    Fungsi utama untuk menjalankan proses klasifikasi tingkat kematangan tomat
    """
    print("=" * 60)
    print("KLASIFIKASI TINGKAT KEMATANGAN TOMAT")
    print("Menggunakan Random Forest dan Color Histogram HSV")
    print("=" * 60)
    
    # Path ke dataset
    dataset_path = "."
    
    # Cek apakah folder dataset ada
    if not os.path.exists(dataset_path):
        print(f"Error: Folder '{dataset_path}' tidak ditemukan!")
        print("Pastikan folder dataset ada dengan struktur:")
        print("./")
        print("  matang/")
        print("  mentah/")
        print("  setengah_matang/")
        return
    
    # Load dataset dan ekstraksi fitur
    print("\n1. MEMBACA DATASET DAN EKSTRAKSI FITUR")
    print("-" * 40)
    
    features, labels, class_names = load_dataset(dataset_path)
    
    print(f"\nTotal gambar yang berhasil diproses: {len(features)}")
    print(f"Dimensi fitur per gambar: {features.shape[1]}")
    
    # Split dataset menjadi training dan testing (80:20)
    print("\n2. SPLIT DATASET")
    print("-" * 40)
    
    X_train, X_test, y_train, y_test = train_test_split(
        features, labels, 
        test_size=0.2, 
        random_state=42, 
        stratify=labels
    )
    
    # Tampilkan jumlah data training dan testing
    print(f"Jumlah data training: {len(X_train)}")
    print(f"Jumlah data testing: {len(X_test)}")
    print(f"Rasio training:testing = {len(X_train)/(len(X_train)+len(X_test)):.1f}:{len(X_test)/(len(X_train)+len(X_test)):.1f}")
    
    # Tampilkan distribusi kelas
    print("\nDistribusi kelas pada data training:")
    for i, class_name in enumerate(class_names):
        count = np.sum(y_train == i)
        print(f"  {class_name}: {count} gambar")
    
    print("\nDistribusi kelas pada data testing:")
    for i, class_name in enumerate(class_names):
        count = np.sum(y_test == i)
        print(f"  {class_name}: {count} gambar")
    
    # Training model Random Forest
    print("\n3. TRAINING MODEL RANDOM FOREST")
    print("-" * 40)
    
    # Inisialisasi model
    rf_model = RandomForestClassifier(
        n_estimators=100,
        random_state=42,
        max_depth=10
    )
    
    # Training model
    rf_model.fit(X_train, y_train)
    print("Model Random Forest telah selesai training!")
    
    # Evaluasi model
    print("\n4. EVALUASI MODEL")
    print("-" * 40)
    
    # Prediksi pada data testing
    y_pred = rf_model.predict(X_test)
    
    # Tampilkan classification report
    print("\nClassification Report:")
    print(classification_report(y_test, y_pred, target_names=class_names))
    
    # Tampilkan confusion matrix dengan visualisasi yang lebih baik
    print("\nConfusion Matrix:")
    cm = plot_confusion_matrix(y_test, y_pred, class_names, 'confusion_matrix_tomat.png')
    print(cm)
    
    # Tampilkan akurasi
    accuracy = np.mean(y_pred == y_test)
    print(f"\nAkurasi model: {accuracy:.4f} ({accuracy*100:.2f}%)")
    
    # Feature importance
    print("\n5. FEATURE IMPORTANCE")
    print("-" * 40)
    
    # Dapatkan feature importance
    importances = rf_model.feature_importances_
    
    # Kelompokkan berdasarkan channel RGB
    bins_per_channel = len(importances) // 3
    r_importance = np.sum(importances[:bins_per_channel])
    g_importance = np.sum(importances[bins_per_channel:2*bins_per_channel])
    b_importance = np.sum(importances[2*bins_per_channel:])
    
    print(f"Importance channel R: {r_importance:.4f} ({r_importance*100:.2f}%)")
    print(f"Importance channel G: {g_importance:.4f} ({g_importance*100:.2f}%)")
    print(f"Importance channel B: {b_importance:.4f} ({b_importance*100:.2f}%)")
    
    # Simpan model
    print("\n6. MENYIMPAN MODEL")
    print("-" * 40)
    
    # Buat folder models jika belum ada
    models_folder = "models"
    if not os.path.exists(models_folder):
        os.makedirs(models_folder)
        print(f"Folder '{models_folder}' dibuat")
    
    # Buat label encoder
    label_encoder = LabelEncoder()
    label_encoder.fit(class_names)
    
    # Simpan model
    try:
        model_file = os.path.join(models_folder, "tomat_classifier.pkl")
        joblib.dump(rf_model, model_file)
        print(f"Model berhasil disimpan: {model_file}")
        
        # Simpan label encoder
        encoder_file = os.path.join(models_folder, "label_encoder.pkl")
        joblib.dump(label_encoder, encoder_file)
        print(f"Label encoder berhasil disimpan: {encoder_file}")
        
        # Simpan metadata
        metadata = {
            'model_type': 'RandomForestClassifier',
            'n_estimators': 100,
            'max_depth': 10,
            'n_features': features.shape[1],
            'classes': class_names,
            'accuracy': accuracy,
            'histogram_bins': (8, 8, 8)
        }
        
        metadata_file = os.path.join(models_folder, "metadata.pkl")
        joblib.dump(metadata, metadata_file)
        print(f"Metadata berhasil disimpan: {metadata_file}")
        
    except Exception as e:
        print(f"Error menyimpan model: {e}")
    
    print("\n" + "=" * 60)
    print("PROSES KLASIFIKASI SELESAI!")
    print("=" * 60)

if __name__ == "__main__":
    main()