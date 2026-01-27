<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'monthly_rent',
        'insurance_amount',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
