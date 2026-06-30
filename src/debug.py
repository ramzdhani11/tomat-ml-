import cv2
import numpy as np
import os

def debug_tomato(image_path):
    image = cv2.imread(image_path)
    if image is None:
        print(f"❌ Tidak bisa baca: {image_path}")
        return

    image = cv2.resize(image, (300, 300))
    blur = cv2.GaussianBlur(image, (7, 7), 0)
    hsv = cv2.cvtColor(blur, cv2.COLOR_BGR2HSV)

    red1   = cv2.inRange(hsv, np.array([0,   60,  40]), np.array([15, 255, 255]))
    red2   = cv2.inRange(hsv, np.array([160,  60,  40]), np.array([180, 255, 255]))
    green  = cv2.inRange(hsv, np.array([35,   40,  40]), np.array([85, 255, 255]))
    orange = cv2.inRange(hsv, np.array([8,    60,  40]), np.array([25, 255, 255]))

    mask_all = cv2.bitwise_or(red1, red2)
    mask_all = cv2.bitwise_or(mask_all, green)
    mask_all = cv2.bitwise_or(mask_all, orange)

    total = 300 * 300
    ratio = cv2.countNonZero(mask_all) / total * 100

    print(f"{'PASS' if ratio >= 25 else 'FAIL'} | {ratio:5.1f}% | {os.path.basename(image_path)}")

# =====================
# Test semua folder dataset
# =====================
folders = [
    r"C:\data_tomat\dataset\matang",
    r"C:\data_tomat\dataset\mentah",
    r"C:\data_tomat\dataset\setengah_matang",
]

for folder in folders:
    print(f"\n=== {os.path.basename(folder)} ===")
    for fname in os.listdir(folder):
        if fname.lower().endswith(('.jpg', '.jpeg', '.png')):
            debug_tomato(os.path.join(folder, fname))