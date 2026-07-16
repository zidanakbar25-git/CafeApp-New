<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailOption extends Model
{
    protected $table = 'order_detail_options';

    protected $fillable = [
        'detail_id',
        'group_name',
        'input_type',
        'option_name',
        'text_value',
    ];

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'detail_id', 'detail_id');
    }
}