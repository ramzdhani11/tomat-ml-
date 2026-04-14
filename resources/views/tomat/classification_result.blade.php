<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Klasifikasi - Maturity Scan Tomat</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <span class="text-2xl font-bold text-red-600">MaturityScan Tomat</span>
                <a href="/" class="text-gray-900 hover:text-red-600 px-3 py-2 text-sm font-medium">Home</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            @if (session('error'))
            <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-6 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                <p class="text-red-800 font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            @if(isset($result) && $result['success'])
                @php
                    $predictionClass = $result['prediction']['class'];
                    $confidence      = $result['prediction']['confidence_percentage'];
                    $probabilities   = $result['prediction']['probabilities'];
                    $metadata        = $result['metadata'];
                @endphp

                <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">Hasil Klasifikasi Tomat</h1>
                    </div>

                    <div class="text-center mb-8">
                        <div class="mb-6">
                            @if($predictionClass == 'matang')
                                <div class="text-6xl mb-4">🍅</div>
                                <span class="px-6 py-3 rounded-full text-white font-bold text-lg bg-green-500">Matang</span>
                            @elseif($predictionClass == 'mentah')
                                <div class="text-6xl mb-4">🟢</div>
                                <span class="px-6 py-3 rounded-full text-white font-bold text-lg bg-yellow-500">Mentah</span>
                            @else
                                <div class="text-6xl mb-4">🟡</div>
                                <span class="px-6 py-3 rounded-full text-white font-bold text-lg bg-purple-500">Setengah Matang</span>
                            @endif
                        </div>

                        <div class="max-w-sm mx-auto mb-8">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span class="font-semibold">Tingkat Kepercayaan</span>
                                <span class="font-bold">{{ number_format($confidence, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"
                                     style="width: {{ $confidence }}%"></div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Probabilitas Setiap Kelas</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($probabilities as $class => $prob)
                                <div class="bg-gray-50 rounded-lg p-4 text-center">
                                    <h4 class="font-semibold mb-2
                                        @if($class == 'matang') text-green-600
                                        @elseif($class == 'mentah') text-yellow-600
                                        @else text-purple-600 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $class)) }}
                                    </h4>
                                    <div class="text-2xl font-bold mb-1">{{ number_format($prob['percentage'], 1) }}%</div>
                                    <div class="text-sm text-gray-500">{{ number_format($prob['probability'], 4) }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Proses</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><strong>Model:</strong> {{ $metadata['model_type'] ?? 'RandomForest' }}</div>
                                <div><strong>Waktu Proses:</strong> {{ $metadata['processing_time_seconds'] ?? '-' }} detik</div>
                                <div><strong>Fitur:</strong> {{ $metadata['features_used'] ?? '-' }}</div>
                                <div><strong>Diproses:</strong> {{ $processedAt ? $processedAt->format('d M Y, H:i:s') : '-' }}</div>
                            </div>
                        </div>

                        <div class="flex justify-center gap-4 flex-wrap">
                            <a href="{{ route('tomat.upload') }}"
                               class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-all">
                                <i class="fas fa-redo mr-2"></i> Upload Gambar Baru
                            </a>
                            <a href="{{ route('tomat.clear') }}"
                               class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-all">
                                <i class="fas fa-trash mr-2"></i> Clear Result
                            </a>
                        </div>
                    </div>
                </div>

            @else
                <div class="text-center">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-4xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Hasil</h3>
                        <p class="text-gray-600 mb-6">Silakan upload gambar tomat terlebih dahulu.</p>
                        <a href="{{ route('tomat.upload') }}"
                           class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg">
                            <i class="fas fa-upload mr-2"></i> Upload Gambar
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </main>

    <footer class="bg-gray-900 text-white py-6 mt-auto">
        <div class="max-w-7xl mx-auto text-center text-gray-400 text-sm">
            © 2026 MaturityScanTomat. All rights reserved.
        </div>
    </footer>

</body>
</html>