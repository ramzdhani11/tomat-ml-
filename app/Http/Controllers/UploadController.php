<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Display the upload page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('landing_page.upload');
    }

    /**
     * Handle the file upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'tomato_image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png',
                'max:5120', // 5MB max
            ],
        ], [
            'tomato_image.required' => 'Gambar tomat harus diunggah.',
            'tomato_image.file' => 'File harus berupa gambar.',
            'tomato_image.image' => 'File harus berupa gambar.',
            'tomato_image.mimes' => 'Format file yang diizinkan: JPG, JPEG, PNG.',
            'tomato_image.max' => 'Ukuran file maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('upload.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('tomato_image');
            
            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $uploadPath = 'uploads/tomatoes';
            if (!Storage::disk('public')->exists($uploadPath)) {
                Storage::disk('public')->makeDirectory($uploadPath);
            }
            
            // Store the file
            $path = $file->storeAs($uploadPath, $filename, 'public');
            
            // Simulate AI classification (replace with actual AI logic)
            $classification = $this->classifyTomato($path);
            
            // Redirect to result page with classification data
            return redirect()->route('upload.result', [
                'image' => $path,
                'category' => $classification['category'],
                'probability' => $classification['probability']
            ]);
                
        } catch (\Exception $e) {
            return redirect()
                ->route('upload.index')
                ->withErrors(['upload' => 'Terjadi kesalahan saat mengunggah file: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the classification result.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function result(Request $request)
    {
        // Get parameters from query string
        $imagePath = $request->get('image');
        $category = $request->get('category', 'matang');
        $probability = (int) $request->get('probability', 85);

        // Validate inputs
        if (!$imagePath) {
            return redirect()->route('upload.index')->withErrors(['error' => 'Tidak ada gambar yang dianalisis.']);
        }

        // Get color classes based on category
        $colors = $this->getCategoryColors($category);
        
        // Get description based on category
        $description = $this->getCategoryDescription($category, $probability);

        return view('result', [
            'imagePath' => $imagePath,
            'category' => $category,
            'probability' => $probability,
            'maturityColor' => $colors['badge'],
            'progressColor' => $colors['progress'],
            'description' => $description
        ]);
    }

    /**
     * Simulate tomato classification (replace with actual AI implementation).
     *
     * @param  string  $imagePath
     * @return array
     */
    private function classifyTomato($imagePath)
    {
        // Simulate AI classification with random results
        // In real implementation, this would call your AI model
        $categories = ['mentah', 'setengah matang', 'matang'];
        $category = $categories[array_rand($categories)];
        $probability = rand(75, 98); // Random probability between 75-98%

        return [
            'category' => $category,
            'probability' => $probability
        ];
    }

    /**
     * Get color classes based on maturity category.
     *
     * @param  string  $category
     * @return array
     */
    private function getCategoryColors($category)
    {
        $colors = [
            'mentah' => [
                'badge' => 'bg-green-500',
                'progress' => 'bg-green-500'
            ],
            'setengah matang' => [
                'badge' => 'bg-yellow-500',
                'progress' => 'bg-yellow-500'
            ],
            'matang' => [
                'badge' => 'bg-red-500',
                'progress' => 'bg-red-500'
            ]
        ];

        return $colors[$category] ?? $colors['matang'];
    }

    /**
     * Get description based on maturity category and probability.
     *
     * @param  string  $category
     * @param  int  $probability
     * @return string
     */
    private function getCategoryDescription($category, $probability)
    {
        $descriptions = [
            'mentah' => "Tomat ini masih dalam tahap pertumbuhan awal dengan tingkat kematangan {$probability}%. Warna hijau menunjukkan bahwa tomat belum matang sempurna dan teksturnya masih keras. Direkomendasikan untuk menunggu beberapa hari agar tomat mencapai kematangan optimal.",
            'setengah matang' => "Tomat ini dalam proses pematangan dengan tingkat kematangan {$probability}%. Perpaduan warna hijau dan merah menunjukkan tomat sedang transisi. Tekstur mulai melunak dan rasa mulai terasa. Ideal untuk penggunaan dalam salad atau masakan yang membutuhkan tomat sedikit masak.",
            'matang' => "Tomat ini telah mencapai kematangan optimal dengan tingkat keyakinan {$probability}%. Warna merah cerah dan tekstur lembut menunjukkan tomat siap dikonsumsi. Kandungan nutrisi dan rasa manis alami telah mencapai puncaknya. Sempurna untuk dimakan langsung atau diolah dalam berbagai masakan."
        ];

        return $descriptions[$category] ?? $descriptions['matang'];
    }

    /**
     * Handle admin login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminLogin(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.string' => 'Kata sandi harus berupa string.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.login')
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->input('email');
        $password = $request->input('password');

        // Query hanya admin dengan role 'admin'
        $user = \DB::table('users')
            ->where('email', $email)
            ->where('role', 'admin')  // Hanya admin yang dapat login
            ->first();

        // Verifikasi password dan pastikan role adalah 'admin'
        if ($user && \Hash::check($password, $user->password) && $user->role === 'admin') {
            // Simpan session
            session([
                'admin_logged_in' => true,
                'admin_user_id' => $user->id,
                'admin_name' => $user->name
            ]);
            
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        }

        // Login gagal
        return redirect()
            ->route('admin.login')
            ->withErrors(['login' => 'Email atau kata sandi salah. Hanya admin yang dapat login.'])
            ->withInput($request->except('password'));
    }
}
