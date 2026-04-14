import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.metrics import confusion_matrix

def plot_confusion_matrix(y_true, y_pred, class_names, save_path='confusion_matrix.png'):
    """
    Menampilkan confusion matrix dalam bentuk heatmap dan menyimpannya sebagai file PNG
    
    Parameters:
    y_true: label aktual (array-like)
    y_pred: label prediksi (array-like)
    class_names: list nama kelas (contoh: ['matang', 'mentah', 'setengah_matang'])
    save_path: path untuk menyimpan file gambar (default: 'confusion_matrix.png')
    
    Returns:
    cm: confusion matrix array
    """
    
    # Hitung confusion matrix
    cm = confusion_matrix(y_true, y_pred)
    
    # Buat figure dengan ukuran yang lebih besar
    plt.figure(figsize=(10, 8))
    
    # Buat heatmap dengan seaborn
    sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', 
                xticklabels=class_names, yticklabels=class_names,
                cbar_kws={'label': 'Jumlah Sampel'},
                square=True,
                linewidths=0.5,
                annot_kws={'size': 12, 'weight': 'bold'})
    
    # Set title dan labels dengan formatting yang lebih baik
    plt.title('Confusion Matrix - Klasifikasi Tingkat Kematangan Tomat', 
              fontsize=16, fontweight='bold', pad=20)
    plt.xlabel('Kelas Prediksi', fontsize=12, fontweight='bold')
    plt.ylabel('Kelas Aktual', fontsize=12, fontweight='bold')
    
    # Rotate labels untuk better readability
    plt.xticks(rotation=45, ha='right')
    plt.yticks(rotation=0)
    
    # Add text summary dengan akurasi
    total_samples = len(y_true)
    accuracy = np.mean(y_true == y_pred) * 100
    plt.figtext(0.5, 0.02, f'Total Sampel: {total_samples} | Akurasi: {accuracy:.2f}%', 
                ha='center', fontsize=11, style='italic')
    
    # Adjust layout untuk prevent label overlap
    plt.tight_layout()
    
    # Save sebagai PNG dengan high quality
    plt.savefig(save_path, dpi=300, bbox_inches='tight', facecolor='white')
    
    # Tampilkan plot
    plt.show()
    
    # Print summary
    print(f"\n{'='*50}")
    print("CONFUSION MATRIX VISUALIZATION")
    print(f"{'='*50}")
    print(f"File disimpan sebagai: {save_path}")
    print(f"Ukuran gambar: 300 DPI")
    print(f"Resolusi: Tinggi")
    print(f"Format: PNG")
    print(f"{'='*50}")
    
    return cm

def plot_confusion_matrix_detailed(y_true, y_pred, class_names, save_path='confusion_matrix_detailed.png'):
    """
    Menampilkan confusion matrix dengan informasi detail (precision, recall, f1-score)
    
    Parameters:
    y_true: label aktual
    y_pred: label prediksi
    class_names: list nama kelas
    save_path: path untuk menyimpan file gambar
    """
    from sklearn.metrics import classification_report, precision_score, recall_score, f1_score
    
    # Hitung confusion matrix
    cm = confusion_matrix(y_true, y_pred)
    
    # Hitung metrics
    precision = precision_score(y_true, y_pred, average=None)
    recall = recall_score(y_true, y_pred, average=None)
    f1 = f1_score(y_true, y_pred, average=None)
    
    # Buat figure dengan 2x2 subplot
    fig, ((ax1, ax2), (ax3, ax4)) = plt.subplots(2, 2, figsize=(15, 12))
    
    # 1. Confusion Matrix
    sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', 
                xticklabels=class_names, yticklabels=class_names,
                ax=ax1, cbar_kws={'label': 'Jumlah Sampel'})
    ax1.set_title('Confusion Matrix', fontweight='bold')
    ax1.set_xlabel('Kelas Prediksi')
    ax1.set_ylabel('Kelas Aktual')
    
    # 2. Precision per kelas
    bars = ax2.bar(class_names, precision, color='skyblue', alpha=0.8)
    ax2.set_title('Precision per Kelas', fontweight='bold')
    ax2.set_ylabel('Precision')
    ax2.set_ylim(0, 1)
    for bar, value in zip(bars, precision):
        ax2.text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.01, 
                f'{value:.3f}', ha='center', va='bottom')
    
    # 3. Recall per kelas
    bars = ax3.bar(class_names, recall, color='lightgreen', alpha=0.8)
    ax3.set_title('Recall per Kelas', fontweight='bold')
    ax3.set_ylabel('Recall')
    ax3.set_ylim(0, 1)
    for bar, value in zip(bars, recall):
        ax3.text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.01, 
                f'{value:.3f}', ha='center', va='bottom')
    
    # 4. F1-Score per kelas
    bars = ax4.bar(class_names, f1, color='salmon', alpha=0.8)
    ax4.set_title('F1-Score per Kelas', fontweight='bold')
    ax4.set_ylabel('F1-Score')
    ax4.set_ylim(0, 1)
    for bar, value in zip(bars, f1):
        ax4.text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.01, 
                f'{value:.3f}', ha='center', va='bottom')
    
    # Overall title
    fig.suptitle('Confusion Matrix & Metrics Detail - Klasifikasi Tomat', 
                 fontsize=16, fontweight='bold')
    
    # Adjust layout
    plt.tight_layout()
    
    # Save dengan high quality
    plt.savefig(save_path, dpi=300, bbox_inches='tight', facecolor='white')
    plt.show()
    
    # Print classification report
    print(f"\nClassification Report:")
    print(classification_report(y_true, y_pred, target_names=class_names))
    
    print(f"\nDetailed confusion matrix disimpan sebagai: {save_path}")
    
    return cm, precision, recall, f1

# Contoh penggunaan
if __name__ == "__main__":
    # Contoh data
    y_true = [0, 1, 2, 0, 1, 2, 0, 1, 2, 0, 1, 2]
    y_pred = [0, 1, 2, 0, 2, 2, 0, 1, 1, 0, 1, 2]
    class_names = ['matang', 'mentah', 'setengah_matang']
    
    # Plot confusion matrix sederhana
    cm = plot_confusion_matrix(y_true, y_pred, class_names, 'example_confusion_matrix.png')
    
    # Plot confusion matrix detail
    cm_detail, precision, recall, f1 = plot_confusion_matrix_detailed(
        y_true, y_pred, class_names, 'example_confusion_matrix_detailed.png'
    )
