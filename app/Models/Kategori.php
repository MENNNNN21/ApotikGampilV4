<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Obat;

class Kategori extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'obat_kategori';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Auto-update slug when updating name
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get all products that belong to this category.
     */
    public function products()
    {
        return $this->hasMany(Obat::class, 'category_id');
    }

    /**
     * Scope to search categories by name
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('slug', 'LIKE', "%{$term}%");
    }

    /**
     * Get formatted name for display
     */
    public function getDisplayNameAttribute()
    {
        return ucfirst($this->name);
    }

    /**
     * Check if category can be deleted
     */
    public function canBeDeleted()
    {
        return $this->products()->count() === 0;
    }
}
