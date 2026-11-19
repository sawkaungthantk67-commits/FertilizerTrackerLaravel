<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageApplication extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'applications';

    protected $fillable = [
        'id',
        'fertilizer_id',
        'fertilizer',
        'unit',
        'unit_price',
        'plot_id',
        'plot',
        'crop',
        'soil_type',
        'plot_size',
        'method',
        'applied_on',
        'quantity',
        'weather',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'float',
            'plot_size' => 'float',
            'applied_on' => 'date:Y-m-d',
            'quantity' => 'float',
        ];
    }
}
