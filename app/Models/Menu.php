<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'image_url',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * A menu item appears in many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'menu_id', 'menu_id');
    }

    /**
     * Format price to Indonesian Rupiah.
     */
    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}