<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TomatController extends Controller
{
    protected $apiUrl = 'http://127.0.0.1:5000/predict';

    /**
     * Show upload form
     */
    public function index()
    {
        return view('tomat.upload');
    }

    /**
     * Handle image upload dan kirim ke Flask API
     */
    public function classify(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:16384',
        ]);

        try {
            $uploadedFile = $request->file('image');

            // Kirim file langsung ke Flask tanpa resize ulang di sini
            // Flask sudah handle resize 256x256 sendiri
            $response = Http::timeout(30)
                ->attach(
                    'image',
                    file_get_contents($uploadedFile->getRealPath()),
                    $uploadedFile->getClientOriginalName()
                )
                ->post($this->apiUrl);

            if ($response->failed()) {
                return back()->with('error', 'Gagal menghubungi API klasifikasi. Status: ' . $response->status());
            }

            $result = $response->json();

            if (!isset($result['success']) || !$result['success']) {
                $errorMsg = $result['message'] ?? 'Prediksi gagal';
                return back()->with('error', 'Error dari API: ' . $errorMsg);
            }

            // Simpan hasil ke session
            session([
                'prediction_result' => $result,
                'processed_at'      => now(),
                'original_size'     => $uploadedFile->getSize(),
            ]);

            return redirect()->route('tomat.result');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'Tidak dapat terhubung ke API Flask. Pastikan server Python sudah berjalan (python app.py).');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show prediction result
     */
    public function getResult()
    {
        $result      = session('prediction_result');
        $processedAt = session('processed_at');

        if (!$result) {
            return redirect()->route('tomat.upload')
                ->with('error', 'Tidak ada hasil prediksi. Silakan upload gambar terlebih dahulu.');
        }

        // ✅ FIX: nama view disesuaikan dengan file yang ada
        return view('tomat.classification_result', compact('result', 'processedAt'));
    }

    /**
     * Check API service status
     * Timeout 3 detik sudah cukup untuk health check lokal
     */
    public function checkService()
    {
        try {
            $response = Http::timeout(3)->get('http://127.0.0.1:5000/health');

            if ($response->successful()) {
                $health = $response->json();
                return response()->json([
                    'success'      => true,
                    'status'       => 'online',
                    'service'      => $health['service'] ?? 'Tomat Classification API',
                    'model_loaded' => $health['model_loaded'] ?? false
                ]);
            }

            return response()->json([
                'success' => false,
                'status'  => 'offline',
                'message' => 'API Flask tidak tersedia'
            ], 503);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Tidak dapat terhubung ke API Flask: ' . $e->getMessage()
            ], 503);
        }
    }

    /**
     * Get model info dari Flask API
     */
    public function getModelInfo()
    {
        try {
            $response = Http::timeout(5)->get('http://127.0.0.1:5000/info');

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil info model'
            ], 503);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 503);
        }
    }
}