<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'member_id',
        'purchase_date',
        'customer_name',
        'customer_phone',
        'customer_email',
        'is_member',
        'total_before_discount',
        'total_after_discount',
        'points_used',
        'points_earned',
        'point_redeem_value',
        'point_earn_spend',
        'max_redeem_percentage',
        'points_discount_amount',
        'cash_paid',
        'change_amount',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'datetime',
            'is_member' => 'boolean',
            'total_before_discount' => 'decimal:2',
            'total_after_discount' => 'decimal:2',
            'point_redeem_value' => 'decimal:2',
            'point_earn_spend' => 'decimal:2',
            'max_redeem_percentage' => 'decimal:2',
            'points_discount_amount' => 'decimal:2',
            'cash_paid' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
