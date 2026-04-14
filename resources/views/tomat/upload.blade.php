<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klasifikasi Kematangan Tomat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .upload-area {
            border: 3px dashed #007bff;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .upload-area:hover { border-color: #0056b3; background-color: #e9ecef; }
        .upload-area.dragover { border-color: #28a745; background-color: #d4edda; }
        .preview-image { max-width: 100%; max-height: 300px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .loading-spinner { display: none; }
        .service-status { padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; }
        .status-online { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-offline { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .tomat-icon { font-size: 4rem; color: #ff6b6b; margin-bottom: 20px; }
        .feature-card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <div class="container py-5">
        <header class="text-center mb-5">
            <h1 class="display-4 fw-bold text-danger">
                <i class="fas fa-lemon me-3"></i>Klasifikasi Kematangan Tomat
            </h1>
            <p class="lead text-muted">Sistem Klasifikasi Tingkat Kematangan Tomat menggunakan Machine Learning</p>
        </header>

        <!-- Service Status -->
        <div id="serviceStatus" class="service-status text-center">
            <i class="fas fa-spinner fa-spin me-2"></i> Memeriksa status layanan...
        </div>

        <!-- Error/Success dari session -->
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card feature-card mb-4">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">
                            <i class="fas fa-upload me-2"></i>Upload Gambar Tomat
                        </h3>

                        {{-- ✅ FIX UTAMA: Pakai form biasa (method POST), bukan AJAX fetch --}}
                        {{-- Controller sudah return redirect(), bukan JSON --}}
                        <form id="uploadForm"
                              action="{{ route('tomat.classify') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <!-- Upload Area -->
                            <div id="uploadArea" class="upload-area mb-4">
                                <i class="fas fa-cloud-upload-alt tomat-icon"></i>
                                <h4>Drag & Drop Gambar di sini</h4>
                                <p class="text-muted">atau klik untuk memilih file</p>
                                <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('imageInput').click()">
                                    <i class="fas fa-folder-open me-2"></i>Pilih File
                                </button>
                                <p class="text-muted mt-3">Format: PNG, JPG, JPEG (Max: 16MB)</p>
                            </div>

                            <!-- Preview Area -->
                            <div id="previewArea" class="text-center mb-4" style="display: none;">
                                <img id="previewImage" class="preview-image mb-3" alt="Preview">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- ✅ Tombol submit biasa, bukan JavaScript fetch --}}
                                    <button type="submit" id="submitBtn" class="btn btn-success btn-lg">
                                        <i class="fas fa-microscope me-2"></i>Klasifikasi
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-lg" onclick="clearImage()">
                                        <i class="fas fa-trash me-2"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Loading - hanya tampil saat form submit -->
                        <div id="loadingSpinner" class="loading-spinner text-center">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Sedang memproses gambar...</p>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="row mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card feature-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-brain fa-3x text-primary mb-3"></i>
                                <h5>Machine Learning</h5>
                                <p class="text-muted small">Menggunakan Random Forest untuk klasifikasi akurat</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card feature-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-palette fa-3x text-success mb-3"></i>
                                <h5>Color Histogram</h5>
                                <p class="text-muted small">Ekstraksi fitur RGB 8x8x8 untuk analisis warna</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card feature-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-tachometer-alt fa-3x text-warning mb-3"></i>
                                <h5>Real-time</h5>
                                <p class="text-muted small">Proses klasifikasi cepat dan hasil instan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedFile = null;

        // ✅ FIX: checkServiceStatus hanya dipanggil SEKALI saat load, tidak ada setInterval
        document.addEventListener('DOMContentLoaded', function() {
            checkServiceStatus();
            // HAPUS: setInterval(checkServiceStatus, 30000) — ini penyebab request berulang
        });

        async function checkServiceStatus() {
            try {
                const response = await fetch('/tomat/service-status');
                const data = await response.json();
                const statusDiv = document.getElementById('serviceStatus');

                if (data.success && data.status === 'online') {
                    statusDiv.className = 'service-status status-online';
                    statusDiv.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        Layanan Aktif - ${data.service || 'Tomat Classification API'}
                        ${data.model_loaded ? ' <i class="fas fa-check-circle ms-2"></i> Model Loaded' : ''}
                    `;
                } else {
                    statusDiv.className = 'service-status status-offline';
                    statusDiv.innerHTML = `<i class="fas fa-times-circle me-2"></i> Layanan Tidak Tersedia`;
                }
            } catch (error) {
                const statusDiv = document.getElementById('serviceStatus');
                statusDiv.className = 'service-status status-offline';
                statusDiv.innerHTML = `<i class="fas fa-times-circle me-2"></i> Tidak dapat terhubung ke API`;
            }
        }

        // File input
        document.getElementById('imageInput').addEventListener('change', function(e) {
            if (e.target.files[0]) handleFileSelect(e.target.files[0]);
        });

        // Drag and drop
        const uploadArea = document.getElementById('uploadArea');
        uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
        uploadArea.addEventListener('dragleave', e => { e.preventDefault(); uploadArea.classList.remove('dragover'); });
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) handleFileSelect(e.dataTransfer.files[0]);
        });

        function handleFileSelect(file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('File tidak valid. Harap upload gambar (PNG, JPG, JPEG)');
                return;
            }
            if (file.size > 16 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 16MB');
                return;
            }
            selectedFile = file;

            // Set file ke input agar ikut terkirim saat form submit
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('imageInput').files = dt.files;

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('uploadArea').style.display = 'none';
                document.getElementById('previewArea').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        function clearImage() {
            selectedFile = null;
            document.getElementById('imageInput').value = '';
            document.getElementById('uploadArea').style.display = 'flex';
            document.getElementById('previewArea').style.display = 'none';
            document.getElementById('previewImage').src = '';
        }

        // ✅ FIX: Saat submit, tampilkan loading dan biarkan form submit normal (bukan fetch)
        document.getElementById('uploadForm').addEventListener('submit', function() {
            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('previewArea').style.display = 'none';
            document.getElementById('submitBtn').disabled = true;
        });
    </script>
</body>
</html>