<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fertilizer extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'type',
        'unit',
        'unit_price',
        'supplier',
        'stock',
        'initial_stock',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'float',
            'stock' => 'float',
            'initial_stock' => 'float',
        ];
    }
}
