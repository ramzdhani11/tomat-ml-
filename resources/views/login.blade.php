<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - TomatoScan Tomat</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles for better focus states */
        .input-field:focus {
            outline: none;
            border-color: #E61E3F;
            box-shadow: 0 0 0 3px rgba(230, 30, 63, 0.1);
        }
        
        .btn-login:hover {
            background-color: #d01835;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(230, 30, 63, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center">
    <!-- Login Card -->
    <div class="w-full max-w-md mx-4">
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <!-- Title & Description -->
            <div class="text-center mb-8">
                <div class="mb-4">
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Login Admin</h1>
                <p class="text-gray-600 text-sm">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
            </div>

            <!-- Login Form -->
            <form class="space-y-6" method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="text-red-800 text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="text-green-800 text-sm">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <!-- Nama Pengguna Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="admin@gmail.com"
                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 transition-all duration-200"
                        required
                        autocomplete="email"
                    >
                </div>

                <!-- Kata Sandi Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan kata sandi"
                            class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 transition-all duration-200"
                            required
                            autocomplete="current-password"
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none"
                        >
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="text-sm text-red-600 hover:text-red-700 transition-colors">
                        Lupa kata sandi?
                    </a>
                </div>

                <!-- Login Button -->
                <div>
                    <button 
                        type="submit" 
                        class="btn-login w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        <span id="btnText">Login</span>
                        <span id="btnLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </form>

            <!-- Additional Links -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun admin? 
                    <a href="{{ route('admin.manage-admin') }}" class="text-red-600 hover:text-red-700 font-medium transition-colors">
                        Hubungi administrator
                    </a>
                </p>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-4 text-center">
            <p class="text-xs text-gray-400">
                🔒 Login aman dengan enkripsi SSL
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="fixed bottom-4 left-4 text-xs text-gray-300 opacity-60">
        Made with ♥ by MaturityScan
    </footer>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        togglePassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        });

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
        });

        // Auto-focus username field on load
        window.addEventListener('load', function() {
            document.getElementById('username').focus();
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement.id === 'username') {
                    e.preventDefault();
                    document.getElementById('password').focus();
                }
            }
        });
    </script>
</body>
</html>
