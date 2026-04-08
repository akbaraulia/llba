<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'point_redeem_value',
        'point_earn_spend',
        'default_max_redeem_percentage',
    ];

    protected function casts(): array
    {
        return [
            'point_redeem_value' => 'decimal:2',
            'point_earn_spend' => 'decimal:2',
            'default_max_redeem_percentage' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'point_redeem_value' => 1,
            'point_earn_spend' => 10000,
            'default_max_redeem_percentage' => 10,
        ]);
    }
}
