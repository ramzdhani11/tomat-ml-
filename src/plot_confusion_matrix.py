import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.metrics import confusion_matrix

def plot_confusion_matrix_heatmap(y_true, y_pred, class_names, save_path='confusion_matrix.png'):
    """
    Menampilkan confusion matrix dalam bentuk heatmap dengan seaborn dan matplotlib
    
    Parameters:
    y_true: label aktual (array)
    y_pred: label prediksi (array)  
    class_names: list nama kelas ['matang', 'mentah', 'setengah_matang']
    save_path: nama file untuk menyimpan gambar PNG
    """
    
    # Hitung confusion matrix
    cm = confusion_matrix(y_true, y_pred)
    
    # Set style
    plt.style.use('default')
    
    # Buat figure
    plt.figure(figsize=(8, 6))
    
    # Buat heatmap dengan seaborn
    sns.heatmap(cm, 
                annot=True,           # Tampilkan angka
                fmt='d',             # Format integer
                cmap='Blues',        # Color scheme
                square=True,         # Bentuk persegi
                cbar=True,           # Tampilkan color bar
                annot_kws={'size': 14, 'weight': 'bold'},  # Style angka
                xticklabels=class_names,  # Label X
                yticklabels=class_names)  # Label Y
    
    # Set title dan labels
    plt.title('Confusion Matrix - Klasifikasi Kematangan Tomat', 
              fontsize=16, fontweight='bold', pad=20)
    plt.xlabel('Kelas Prediksi', fontsize=12, fontweight='bold')
    plt.ylabel('Kelas Aktual', fontsize=12, fontweight='bold')
    
    # Rotate labels agar lebih mudah dibaca
    plt.xticks(rotation=0, fontsize=11)
    plt.yticks(rotation=0, fontsize=11)
    
    # Tambahkan grid
    plt.grid(False)
    
    # Save sebagai PNG dengan high quality
    plt.savefig(save_path, dpi=300, bbox_inches='tight', facecolor='white')
    
    # Tampilkan plot
    plt.show()
    
    # Print informasi
    print(f"Confusion matrix berhasil disimpan sebagai: {save_path}")
    print(f"Ukuran: 300 DPI")
    print(f"Format: PNG")
    
    return cm

# Contoh penggunaan dengan data sampel
def example_usage():
    """
    Contoh penggunaan fungsi confusion matrix
    """
    # Data sampel (contoh hasil klasifikasi)
    y_true = [0, 0, 1, 1, 2, 2, 0, 1, 2, 0, 1, 2, 0, 1, 2]
    y_pred = [0, 0, 1, 2, 2, 2, 0, 1, 1, 0, 1, 2, 0, 1, 2]
    
    # Label kelas
    class_names = ['matang', 'mentah', 'setengah_matang']
    
    # Plot confusion matrix
    cm = plot_confusion_matrix_heatmap(y_true, y_pred, class_names, 'confusion_matrix_tomat.png')
    
    # Tampilkan confusion matrix dalam bentuk array
    print("\nConfusion Matrix Array:")
    print(cm)
    
    # Hitung akurasi
    accuracy = np.trace(cm) / np.sum(cm) * 100
    print(f"\nAkurasi: {accuracy:.2f}%")

if __name__ == "__main__":
    example_usage()
