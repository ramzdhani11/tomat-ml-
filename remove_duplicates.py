import os
import hashlib

def get_file_hash(filepath):
    # Menggunakan MD5 untuk mendapatkan "sidik jari" (hash) unik dari setiap file
    hasher = hashlib.md5()
    with open(filepath, 'rb') as f:
        buf = f.read()
        hasher.update(buf)
    return hasher.hexdigest()

def find_and_delete_duplicates(dataset_dir):
    seen_hashes = {}
    duplicates = []
    
    # Mencari ke dalam folder matang, mentah, dan setengah_matang
    for root, _, files in os.walk(dataset_dir):
        for filename in files:
            if filename.lower().endswith(('.png', '.jpg', '.jpeg')):
                filepath = os.path.join(root, filename)
                file_hash = get_file_hash(filepath)
                
                if file_hash in seen_hashes:
                    duplicates.append(filepath)
                    print(f"Hapus duplikat: {filepath}")
                else:
                    seen_hashes[file_hash] = filepath
                    
    # Melakukan penghapusan file
    for dup in duplicates:
        os.remove(dup)
        
    print(f"\nProses selesai! Total gambar duplikat yang dihapus: {len(duplicates)}")

if __name__ == "__main__":
    dataset_path = 'dataset'
    if not os.path.exists(dataset_path):
        dataset_path = os.path.join('..', 'dataset') # jika dijalankan di dalam folder notebooks
        
    find_and_delete_duplicates(dataset_path)
