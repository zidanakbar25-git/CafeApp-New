<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CafeTable extends Model
{
    protected $table = 'cafe_tables';
    protected $primaryKey = 'table_id';

    protected $fillable = [
        'table_number',
        'qr_token',
    ];

    public function getRouteKeyName(): string
    {
        return 'table_id';
    }

    /**
     * URL QR Code — tambah ?scan=1 supaya trigger generate session
     */
    public function getQrUrlAttribute(): string
    {
        return url('/table/' . $this->table_number . '?scan=1');
    }
}