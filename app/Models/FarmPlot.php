<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmPlot extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'plots';

    protected $fillable = [
        'id',
        'name',
        'crop',
        'soil_type',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'float',
        ];
    }
}
