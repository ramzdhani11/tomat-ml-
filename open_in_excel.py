import pandas as pd
import os

def open_csv_in_excel(csv_file):
    """
    Membuka file CSV di Excel menggunakan pandas
    """
    try:
        # Baca file CSV
        df = pd.read_csv(csv_file)
        
        print(f"File CSV berhasil dibaca: {csv_file}")
        print(f"Jumlah data: {len(df)} baris")
        print(f"Kolom: {list(df.columns)}")
        print("\n5 Data Pertama:")
        print(df.head())
        
        # Simpan sebagai file Excel
        excel_file = csv_file.replace('.csv', '.xlsx')
        df.to_excel(excel_file, index=False)
        
        print(f"\nFile Excel telah dibuat: {excel_file}")
        print(f"Anda dapat membuka file ini di Excel untuk melihat data dalam format tabel yang lebih baik.")
        
        return excel_file
        
    except Exception as e:
        print(f"Error: {e}")
        return None

if __name__ == "__main__":
    csv_file = "fitur_tomat.csv"
    excel_file = open_csv_in_excel(csv_file)
    
    if excel_file:
        print(f"\nUntuk membuka di Excel secara otomatis, Anda bisa:")
        print(f"1. Double-click file: {excel_file}")
        print(f"2. Atau jalankan perintah: start {excel_file}")
