<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Unit extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'type',
        'title',
        'description',
        'location',
        'city_id',
        'area_id',
        'slug',
        'status',
        'bedrooms',
        'bathrooms',
        'area',
        'is_featured',
    ];

    public array $translatable = ['title', 'description', 'location'];

    protected $casts = [
        'is_featured' => 'boolean',
        'area' => 'decimal:2',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public');

        $this->addMediaCollection('videos')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued();

        // 16:9 banner for main display (1920x1080)
        $this->addMediaConversion('banner')
            ->fit(Fit::Crop, 1920, 1080)
            ->sharpen(5)
            ->performOnCollections('images')
            ->nonQueued();

        // 16:9 card for listings (640x360)
        $this->addMediaConversion('card')
            ->fit(Fit::Crop, 640, 360)
            ->sharpen(5)
            ->performOnCollections('images')
            ->nonQueued();
    }

    public function rentalDetail(): HasOne
    {
        return $this->hasOne(RentalDetail::class);
    }

    public function saleDetail(): HasOne
    {
        return $this->hasOne(SaleDetail::class);
    }

    public function constructionDetail(): HasOne
    {
        return $this->hasOne(ConstructionDetail::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function unitArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function getFullLocationAttribute(): string
    {
        $parts = [];
        if ($this->unitArea) {
            $parts[] = $this->unitArea->name;
        }
        if ($this->city) {
            $parts[] = $this->city->name;
        }

        return implode(', ', $parts) ?: $this->location ?? '';
    }

    public function scopeRental($query)
    {
        return $query->where('type', 'rental');
    }

    public function scopeSale($query)
    {
        return $query->where('type', 'sale');
    }

    public function scopeUnderConstruction($query)
    {
        return $query->where('type', 'under_construction');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
