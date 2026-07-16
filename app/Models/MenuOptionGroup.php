<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOptionGroup extends Model
{
    protected $table = 'menu_option_groups';
    protected $primaryKey = 'group_id';

    protected $fillable = [
        'menu_id',
        'name',
        'input_type',
        'is_required',
        'min_select',
        'max_select',
        'placeholder',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    public function options()
    {
        return $this->hasMany(MenuOption::class, 'group_id', 'group_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order');
    }
}