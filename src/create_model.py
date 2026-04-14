import joblib
import numpy as np
from sklearn.ensemble import RandomForestClassifier

print("Membuat model Random Forest untuk testing...")

# Buat model Random Forest
model = RandomForestClassifier(n_estimators=10, random_state=42)

# Training dengan data dummy (24 fitur untuk histogram RGB 8x8x8)
X_dummy = np.random.rand(100, 24)
y_dummy = np.random.choice([0, 1, 2], 100)
model.fit(X_dummy, y_dummy)

# Simpan model
joblib.dump(model, 'model_tomat.pkl')

print("Model berhasil dibuat dan disimpan sebagai 'model_tomat.pkl'")
print(f"Tipe model: {type(model).__name__}")
print(f"Jumlah fitur: {model.n_features_in_}")
print(f"Jumlah kelas: {len(model.classes_)}")
print(f"Kelas: {model.classes_}")
