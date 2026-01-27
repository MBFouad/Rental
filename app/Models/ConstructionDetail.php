<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'total_price',
        'down_payment_amount',
        'down_payment_percentage',
        'expected_completion',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'down_payment_percentage' => 'decimal:2',
        'expected_completion' => 'date',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function paymentPlans(): HasMany
    {
        return $this->hasMany(PaymentPlan::class);
    }
}
