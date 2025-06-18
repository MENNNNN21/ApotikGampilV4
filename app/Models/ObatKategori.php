<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ObatKategori extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'obat_kategori';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'image'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($kategori) {
            $kategori->slug = Str::slug($kategori->nama);
        });

        static::updating(function ($kategori) {
            $kategori->slug = Str::slug($kategori->nama);
        });
    }

    /**
     * Get the obat for the kategori.
     */
    public function obat()
    {
        return $this->hasMany(Obat::class, 'kategori_id');
    }
}