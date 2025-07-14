<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class FavoriteController extends Controller
{
    // Konstruktor tidak diperlukan karena middleware auth sudah diterapkan di route
    
    /**
     * Toggle status favorit cafe
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $cafeId
     * @return \Illuminate\Http\Response
     */
    public function toggleFavorite(Request $request, $cafeId)
    {
        try {
            // Validasi input
            if (!$cafeId || !is_numeric($cafeId)) {
                Log::error('Invalid cafe ID provided: ' . $cafeId);
                return response()->json(['success' => false, 'message' => 'ID cafe tidak valid'], 400);
            }

            // Cek apakah cafe dengan ID tersebut ada
            $cafe = Cafe::find($cafeId);
            if (!$cafe) {
                Log::error('Cafe not found with ID: ' . $cafeId);
                return response()->json(['success' => false, 'message' => 'Cafe tidak ditemukan'], 404);
            }

            $user = Auth::user();
            if (!$user) {
                Log::error('User not authenticated for favorite toggle');
                return response()->json(['success' => false, 'message' => 'Pengguna tidak terautentikasi'], 401);
            }

            Log::info('Toggling favorite for user ID: ' . $user->id . ' and cafe ID: ' . $cafeId);

            // Pemeriksaan apakah sudah favorit secara langsung menggunakan query
            $existingFavorite = DB::table('favorites')
                ->where('user_id', $user->id)
                ->where('cafe_id', $cafeId)
                ->first();

            if ($existingFavorite) {
                // Hapus favorit
                DB::table('favorites')
                    ->where('user_id', $user->id)
                    ->where('cafe_id', $cafeId)
                    ->delete();
                
                $isFavorited = false;
                Log::info('Favorite removed for user ID: ' . $user->id . ' and cafe ID: ' . $cafeId);
            } else {
                // Tambahkan favorit
                DB::table('favorites')->insert([
                    'user_id' => $user->id,
                    'cafe_id' => $cafeId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $isFavorited = true;
                Log::info('Favorite added for user ID: ' . $user->id . ' and cafe ID: ' . $cafeId);
            }
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'isFavorited' => $isFavorited
                ]);
            }
            
            return back()->with('success', $isFavorited ? 'Cafe ditambahkan ke favorit' : 'Cafe dihapus dari favorit');
        } catch (Exception $e) {
            Log::error('Error toggling favorite: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat mengubah status favorit.');
        }
    }

    /**
     * Menampilkan daftar cafe favorit user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('cafe')->get();
        
        return view('favorites.index', [
            'favorites' => $favorites
        ]);
    }
} 