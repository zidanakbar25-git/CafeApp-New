<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'integer',
        'subtotal'   => 'integer',
    ];

    // ── Relationships ───────────────────────────────────────────────

    /**
     * Each detail belongs to a specific menu item.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    /**
     * Each detail belongs to a parent order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // ── Helpers ─────────────────────────────────────────────────────

    /**
     * Recalculate subtotal based on current quantity × unit_price.
     */
    public function recalculateSubtotal(): void
    {
        $this->subtotal = $this->quantity * $this->unit_price;
        $this->save();
    }

    /**
     * Format subtotal to Rupiah string.
     */
    public function getSubtotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Format unit_price to Rupiah string.
     */
    public function getUnitPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }
}