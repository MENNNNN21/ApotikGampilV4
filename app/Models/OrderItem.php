<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'obat_id',
        'product_name',
        'price',
        'quantity',
        'weight',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:3',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the order that owns the order item
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product (obat) that belongs to the order item
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get total weight for this item
     */
    public function getTotalWeightAttribute(): float
    {
        return $this->weight * $this->quantity;
    }
}