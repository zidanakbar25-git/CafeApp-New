<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    // aktifkan timestamps
    public $timestamps = true;

    protected $fillable = [
        'table_id',
        'order_code',
        'customer_name',
        'status',
        'total_amount',
        'payment_method',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /**
     * Relasi order details
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    /**
     * Relasi payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    /**
     * Recalculate total order
     */
    public function recalculateTotal(): void
    {
        $this->total_amount = $this->orderDetails()->sum('subtotal');
        $this->save();
    }

    /**
     * Format Rupiah
     */
    public function getTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function cafeTable()
{
    return $this->belongsTo(\App\Models\CafeTable::class, 'table_id', 'table_id');
}
}