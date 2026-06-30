import cv2
import numpy as np
import pandas as pd
import os

def extract_hsv_features(image_path, label):
    """
    Ekstraksi fitur HSV dari gambar
    Mengembalikan mean dari setiap channel HSV
    """
    try:
        # Baca gambar (OpenCV membaca dalam format BGR)
        image = cv2.imread(image_path)
        if image is None:
            return None
        
        # Konversi dari BGR ke HSV
        hsv_image = cv2.cvtColor(image, cv2.COLOR_BGR2HSV)
        
        # Hitung mean untuk setiap channel
        h_mean = np.mean(hsv_image[:, :, 0])  # Hue
        s_mean = np.mean(hsv_image[:, :, 1])  # Saturation
        v_mean = np.mean(hsv_image[:, :, 2])  # Value
        
        return h_mean, s_mean, v_mean, label
        
    except Exception as e:
        print(f"Error processing {image_path}: {e}")
        return None

def process_dataset(dataset_path):
    """
    Memproses seluruh dataset gambar tomat
    """
    # List untuk menyimpan hasil ekstraksi
    features = []
    
    # Folder labels
    labels = ['mentah', 'setengah_matang', 'matang']
    
    print("Memproses dataset gambar tomat...")
    print("=" * 50)
    
    for label in labels:
        folder_path = os.path.join(dataset_path, label)
        
        if not os.path.exists(folder_path):
            print(f"Folder {folder_path} tidak ditemukan!")
            continue
            
        # Dapatkan semua file gambar dalam folder
        image_files = [f for f in os.listdir(folder_path) 
                      if f.lower().endswith(('.png', '.jpg', '.jpeg', '.bmp', '.gif'))]
        
        print(f"Memproses {len(image_files)} gambar di folder '{label}'...")
        
        for image_file in image_files:
            image_path = os.path.join(folder_path, image_file)
            result = extract_hsv_features(image_path, label)
            
            if result is not None:
                features.append(result)
    
    return features

def main():
    # Path ke dataset
    dataset_path = "dataset"
    
    # Proses dataset
    features = process_dataset(dataset_path)
    
    if not features:
        print("Tidak ada data yang berhasil diproses!")
        return
    
    # Buat DataFrame
    df = pd.DataFrame(features, columns=['Hue', 'Saturation', 'Value', 'Label'])
    
    print("\n" + "=" * 50)
    print("HASIL EKSTRAKSI FITUR HSV")
    print("=" * 50)
    
    # Tampilkan informasi dataset
    print(f"Total gambar yang diproses: {len(df)}")
    print(f"Distribusi label:")
    print(df['Label'].value_counts())
    print()
    
    # Tampilkan 10 data pertama
    print("10 Data Pertama:")
    print(df.head(10))
    print()
    
    # Tampilkan statistik deskriptif
    print("Statistik Deskriptif:")
    print(df.describe())
    print()
    
    # Simpan ke CSV
    output_file = "fitur_tomat.csv"
    df.to_csv(output_file, index=False)
    print(f"Hasil disimpan ke file: {output_file}")
    
    return df

if __name__ == "__main__":
    df = main()
