<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_detail_id',
        'duration_years',
        'monthly_installment',
    ];

    protected $casts = [
        'duration_years' => 'integer',
        'monthly_installment' => 'decimal:2',
    ];

    public function constructionDetail(): BelongsTo
    {
        return $this->belongsTo(ConstructionDetail::class);
    }
}
