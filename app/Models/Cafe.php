<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cafe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'lokasi',
        'area',
        'gambar',
        'latitude',
        'longitude',
        'harga_termurah',
        'harga_termahal'
    ];

    /**
     * Relasi ke cafe_ratings
     */
    public function ratings()
    {
        return $this->hasMany(CafeRating::class);
    }

    /**
     * Pengguna yang memfavoritkan cafe ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Cek apakah cafe ini difavoritkan oleh user tertentu
     *
     * @param int $userId
     * @return bool
     */
    public function isFavoritedBy($userId)
    {
        return $this->favoritedBy()->where('user_id', $userId)->exists();
    }

    /**
     * Mendapatkan rating untuk kategori tertentu
     */
    public function getCategoryRating($categoryId)
    {
        $rating = $this->ratings()->whereHas('subcategory', function($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })->with('subcategory')->first();
        
        return $rating ? $rating->subcategory : null;
    }

    /**
     * Mendapatkan nilai numerik untuk kategori tertentu
     */
    public function getCategoryValue($categoryId)
    {
        $subcategory = $this->getCategoryRating($categoryId);
        return $subcategory ? $subcategory->value : null;
    }

    /**
     * Mendapatkan nama subcategory untuk kategori tertentu
     */
    public function getCategoryName($categoryId)
    {
        $subcategory = $this->getCategoryRating($categoryId);
        return $subcategory ? $subcategory->name : null;
    }

    /**
     * Mendapatkan nilai WiFi
     */
    public function getWifiAttribute()
    {
        $wifiCategory = Category::where('name', 'Kecepatan WiFi')->first();
        if (!$wifiCategory) return null;
        
        return $this->getCategoryRating($wifiCategory->id);
    }

    /**
     * Mendapatkan nilai Jarak dengan Pusat Kota
     */
    public function getJarakKampusAttribute()
    {
        $jarakCategory = Category::where('name', 'Jarak dengan Pusat Kota')->first();
        if (!$jarakCategory) return null;
        
        return $this->getCategoryRating($jarakCategory->id);
    }

    /**
     * Mendapatkan nilai Fasilitas
     */
    public function getFasilitasAttribute()
    {
        $fasilitasCategory = Category::where('name', 'Fasilitas')->first();
        if (!$fasilitasCategory) return null;
        
        return $this->getCategoryRating($fasilitasCategory->id);
    }

    /**
     * Mendapatkan nilai Harga
     */
    public function getHargaAttribute()
    {
        $hargaCategory = Category::where('name', 'Harga')->first();
        if (!$hargaCategory) return null;
        
        return $this->getCategoryRating($hargaCategory->id);
    }

    /**
     * Relasi ke cafe_images
     */
    public function images()
    {
        return $this->hasMany(CafeImage::class);
    }
}
