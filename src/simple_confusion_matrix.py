import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.metrics import confusion_matrix

def plot_confusion_matrix(y_true, y_pred, class_names, save_path='confusion_matrix.png'):
    """
    Fungsi sederhana untuk menampilkan confusion matrix heatmap
    
    Parameters:
    y_true: label aktual
    y_pred: label prediksi
    class_names: list nama kelas ['matang', 'mentah', 'setengah_matang']
    save_path: nama file PNG
    """
    # Hitung confusion matrix
    cm = confusion_matrix(y_true, y_pred)
    
    # Plot heatmap
    plt.figure(figsize=(8, 6))
    sns.heatmap(cm, annot=True, fmt='d', cmap='Blues',
                xticklabels=class_names, yticklabels=class_names)
    
    plt.title('Confusion Matrix - Klasifikasi Tomat', fontweight='bold')
    plt.xlabel('Kelas Prediksi')
    plt.ylabel('Kelas Aktual')
    
    # Save dan tampilkan
    plt.savefig(save_path, dpi=300, bbox_inches='tight')
    plt.show()
    
    return cm

# Contoh penggunaan
if __name__ == "__main__":
    # Data contoh
    y_true = [0, 1, 2, 0, 1, 2, 0, 1, 2]
    y_pred = [0, 1, 2, 0, 2, 2, 0, 1, 1]
    
    # Label kelas
    class_names = ['matang', 'mentah', 'setengah_matang']
    
    # Plot
    cm = plot_confusion_matrix(y_true, y_pred, class_names, 'confusion_matrix.png')
    print("Confusion Matrix:")
    print(cm)
