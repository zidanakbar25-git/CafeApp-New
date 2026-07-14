<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'admin_id',
        'payment_method',
        'payment_status',
        'paid_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}