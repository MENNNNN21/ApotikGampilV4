<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'shipping_cost',
        'payment_method',   // <-- TAMBAHKAN INI
        'shipping_method',
        'total',
        'status',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'city',
        'district',
        'postal_code',
        'courier_name',
        'courier_service',
        'tracking_number',
        'tracking_id', // Added for Biteship
        'waybill_id', // Added for Biteship
        'courier_details',
        'biteship_order_id',
        'notes',
        'shipped_at',
        'delivered_at',
        'payment_proof'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'courier_details' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Generate order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $date . $newNumber;
    }

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        // 'OrderItem::class' adalah nama model untuk item pesanan Anda.
        // 'order_id' adalah foreign key di tabel order_items (asumsi default Laravel).
        return $this->hasMany(OrderItem::class);
    }
     

    /**
     * Get the order items
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get formatted shipping cost
     */
    public function getFormattedShippingCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    /**
     * Get status label
     */
    // di dalam app/Models/Order.php

public function getStatusLabelAttribute(): string
{
    return match($this->status) {
        'pending' => 'Menunggu Pembayaran',
        'pending_verification' => 'Menunggu Verifikasi', // <-- TAMBAHKAN INI
        'processing' => 'Sedang Diproses',
        'shipped' => 'Dalam Pengiriman',
        'delivered' => 'Terkirim',
        'cancelled' => 'Dibatalkan',
        default => 'Unknown'
    };
}

public function getStatusColorAttribute(): string
{
    return match($this->status) {
        'pending' => 'warning',
        'pending_verification' => 'info', // <-- TAMBAHKAN INI
        'processing' => 'primary',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger',
        default => 'secondary'
    };
}
}