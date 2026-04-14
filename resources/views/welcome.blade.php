<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Klasifikasi Kematangan Tomat</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl font-bold text-red-600">MaturityScan Tomat</span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#" class="text-gray-900 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Beranda</a>
                        <a href="{{ route('about') }}" class="text-gray-500 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Tentang kami</a>
                        <a href="{{ route('login') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                            Login
                        </a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-red-50 to-orange-50 py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Klasifikasi Kematangan Tomat Otomatis
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed text-justify">
                        MaturityScan Tomat adalah aplikasi berbasis website yang membantu Anda menentukan tingkat kematangan tomat secara otomatis menggunakan analisis citra digital. Sistem memanfaatkan metode Color Histogram dan algoritma Random Forest untuk memberikan hasil klasifikasi yang cepat dan objektif.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('tomat.upload') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 inline-block text-center">
                           Mulai Klasifikasi
                        </a>
                        <a href="{{ route('about') }}" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-8 rounded-lg shadow-md border border-gray-200 transition-all duration-200 inline-block text-center">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <img src="{{ asset('assets/images/tomatt.png') }}" 
                             alt="Fresh Tomatoes" 
                             class="rounded-lg shadow-md w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Three Cards Section - Tingkat Kematangan -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Tingkat Kematangan Tomat</h2>
                <p class="text-lg text-gray-600">Sistem kami mengklasifikasikan tomat menjadi tiga tingkat kematangan</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Mentah Card -->
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-32 h-32 bg-green-500 rounded-full mx-auto mb-6 flex items-center justify-center p-2">
                        <img src="{{ asset('assets/images/mentah.jpg') }}" alt="Tomat Mentah" class="w-full h-full object-contain rounded-full">
                    </div>
                    <h3 class="text-2xl font-bold text-green-800 mb-4">Mentah</h3>
                    <p class="text-gray-600 mb-4">Tomat yang belum matang sempurna, berwarna hijau dan tekstur masih keras.</p>
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold inline-block">
                        Warna: Hijau
                    </div>
                </div>

                <!-- Setengah Matang Card -->
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-32 h-32 bg-yellow-500 rounded-full mx-auto mb-6 flex items-center justify-center p-2">
                        <img src="{{ asset('assets/images/setengahmateng.jpg') }}" alt="Tomat Setengah Matang" class="w-full h-full object-contain rounded-full">
                    </div>
                    <h3 class="text-2xl font-bold text-yellow-800 mb-4">Setengah Matang</h3>
                    <p class="text-gray-600 mb-4">Tomat dalam proses pematangan, perpaduan warna hijau dan merah.</p>
                    <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold inline-block">
                        Warna: Kuning-Hijau
                    </div>
                </div>

                <!-- Matang Card -->
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-32 h-32 bg-red-500 rounded-full mx-auto mb-6 flex items-center justify-center p-2">
                        <img src="{{ asset('assets/images/matang.jpg') }}" alt="Tomat Matang" class="w-full h-full object-contain rounded-full">
                    </div>
                    <h3 class="text-2xl font-bold text-red-800 mb-4">Matang</h3>
                    <p class="text-gray-600 mb-4">Tomat matang sempurna, berwarna merah cerah dan tekstur lembut.</p>
                    <div class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-semibold inline-block">
                        Warna: Merah
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Bagaimana Cara Kerjanya?</h2>
                <p class="text-lg text-gray-600">Tiga langkah mudah untuk mengklasifikasikan kematangan tomat Anda</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="bg-white rounded-xl p-4 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 text-center min-h-[280px] md:min-h-[320px]">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full mx-auto mb-4 md:mb-6 flex items-center justify-center">
                            <i class="fas fa-camera text-lg md:text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Unggah Gambar</h3>
                        <p class="text-sm md:text-base text-gray-600 text-justify md:text-center">Ambil foto tomat atau unggah gambar dari galeri perangkat Anda.</p>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">→</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative">
                    <div class="bg-white rounded-xl p-4 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 text-center min-h-[280px] md:min-h-[320px]">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-purple-500 rounded-full mx-auto mb-4 md:mb-6 flex items-center justify-center">
                            <i class="fas fa-palette text-lg md:text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Ekstraksi Fitur Warna</h3>
                        <p class="text-sm md:text-base text-gray-600 text-justify md:text-center">Sistem mengekstraksi fitur warna menggunakan<br>Color Histogram (RGB) untuk membaca distribusi warna tomat.</p>
                    </div>
                    <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm">→</span>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="bg-white rounded-xl p-4 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 text-center min-h-[280px] md:min-h-[320px]">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-green-500 rounded-full mx-auto mb-4 md:mb-6 flex items-center justify-center">
                            <i class="fas fa-brain text-lg md:text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-4">Klasifikasi & Hasil</h3>
                        <p class="text-sm md:text-base text-gray-600 text-justify md:text-center">Fitur warna diproses dengan Random Forest (RF)<br>untuk menentukan tingkat kematangan secara real-time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Tentang</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Connect -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-400 text-sm">© 2026 MaturityScanTomat. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
