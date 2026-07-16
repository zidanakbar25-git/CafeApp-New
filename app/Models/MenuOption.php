<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOption extends Model
{
    protected $table = 'menu_options';
    protected $primaryKey = 'option_id';

    protected $fillable = [
        'group_id',
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(MenuOptionGroup::class, 'group_id', 'group_id');
    }
}