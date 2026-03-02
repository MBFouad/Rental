<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use App\Models\ConstructionDetail;
use App\Models\PaymentPlan;
use App\Models\RentalDetail;
use App\Models\SaleDetail;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    /**
     * Available property images for seeding.
     */
    protected array $propertyImages = [
        'apartment-1.jpg',
        'apartment-2.jpg',
        'villa-1.jpg',
        'villa-2.jpg',
        'house-1.jpg',
        'house-2.jpg',
        'interior-1.jpg',
        'interior-2.jpg',
        'luxury-1.jpg',
        'luxury-2.jpg',
    ];

    public function run(): void
    {
        // Get cities and areas for assignment
        $cairo = City::where('slug', 'cairo')->first();
        $alexandria = City::where('slug', 'alexandria')->first();
        $newCairo = Area::where('slug', 'new-cairo')->first();
        $smouha = Area::where('slug', 'smouha')->first();
        $fifthSettlement = Area::where('slug', 'fifth-settlement')->first();
        $sheikhZayed = Area::where('slug', 'sheikh-zayed')->first();

        // Rental Units
        $rentalUnits = [
            [
                'title' => ['ar' => 'شقة فاخرة في القاهرة', 'en' => 'Luxury Apartment in Cairo'],
                'description' => ['ar' => 'شقة فاخرة مع إطلالة رائعة على المدينة', 'en' => 'Luxury apartment with amazing city view'],
                'location' => ['ar' => 'القاهرة الجديدة', 'en' => 'New Cairo'],
                'city_id' => $cairo?->id,
                'area_id' => $newCairo?->id,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 180,
                'monthly_rent' => 8000,
                'insurance_amount' => 16000,
            ],
            [
                'title' => ['ar' => 'استوديو عصري', 'en' => 'Modern Studio'],
                'description' => ['ar' => 'استوديو عصري مفروش بالكامل', 'en' => 'Fully furnished modern studio'],
                'location' => ['ar' => 'سموحة، الإسكندرية', 'en' => 'Smouha, Alexandria'],
                'city_id' => $alexandria?->id,
                'area_id' => $smouha?->id,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area' => 60,
                'monthly_rent' => 3500,
                'insurance_amount' => 7000,
            ],
        ];

        foreach ($rentalUnits as $data) {
            $unit = Unit::create([
                'type' => 'rental',
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'city_id' => $data['city_id'],
                'area_id' => $data['area_id'],
                'slug' => Str::slug($data['title']['en']),
                'status' => 'available',
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'area' => $data['area'],
                'is_featured' => true,
            ]);

            RentalDetail::create([
                'unit_id' => $unit->id,
                'monthly_rent' => $data['monthly_rent'],
                'insurance_amount' => $data['insurance_amount'],
            ]);

            $this->attachRandomImages($unit);
        }

        // Sale Units
        $saleUnits = [
            [
                'title' => ['ar' => 'فيلا راقية', 'en' => 'Elegant Villa'],
                'description' => ['ar' => 'فيلا راقية مع حديقة خاصة ومسبح', 'en' => 'Elegant villa with private garden and pool'],
                'location' => ['ar' => 'التجمع الخامس', 'en' => 'Fifth Settlement'],
                'city_id' => $cairo?->id,
                'area_id' => $fifthSettlement?->id,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area' => 450,
                'sale_price' => 2500000,
                'is_negotiable' => true,
            ],
            [
                'title' => ['ar' => 'شقة دوبلكس', 'en' => 'Duplex Apartment'],
                'description' => ['ar' => 'شقة دوبلكس واسعة بتشطيب فاخر', 'en' => 'Spacious duplex apartment with luxury finishing'],
                'location' => ['ar' => 'الشيخ زايد', 'en' => 'Sheikh Zayed'],
                'city_id' => $cairo?->id,
                'area_id' => $sheikhZayed?->id,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area' => 280,
                'sale_price' => 1200000,
                'is_negotiable' => false,
            ],
        ];

        foreach ($saleUnits as $data) {
            $unit = Unit::create([
                'type' => 'sale',
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'city_id' => $data['city_id'],
                'area_id' => $data['area_id'],
                'slug' => Str::slug($data['title']['en']),
                'status' => 'available',
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'area' => $data['area'],
                'is_featured' => true,
            ]);

            SaleDetail::create([
                'unit_id' => $unit->id,
                'sale_price' => $data['sale_price'],
                'is_negotiable' => $data['is_negotiable'],
            ]);

            $this->attachRandomImages($unit);
        }

        // Under Construction Units
        $constructionUnits = [
            [
                'title' => ['ar' => 'مشروع برج السماء', 'en' => 'Sky Tower Project'],
                'description' => ['ar' => 'وحدات سكنية فاخرة في برج السماء', 'en' => 'Luxury residential units in Sky Tower'],
                'location' => ['ar' => 'القاهرة الجديدة', 'en' => 'New Cairo'],
                'city_id' => $cairo?->id,
                'area_id' => $newCairo?->id,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 200,
                'total_price' => 1500000,
                'down_payment_amount' => 300000,
                'down_payment_percentage' => 20,
                'expected_completion' => '2027-06-01',
                'payment_plans' => [
                    ['duration_years' => 3, 'monthly_installment' => 33333],
                    ['duration_years' => 5, 'monthly_installment' => 20000],
                    ['duration_years' => 10, 'monthly_installment' => 10000],
                ],
            ],
        ];

        foreach ($constructionUnits as $data) {
            $unit = Unit::create([
                'type' => 'under_construction',
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'city_id' => $data['city_id'],
                'area_id' => $data['area_id'],
                'slug' => Str::slug($data['title']['en']),
                'status' => 'available',
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'area' => $data['area'],
                'is_featured' => true,
            ]);

            $constructionDetail = ConstructionDetail::create([
                'unit_id' => $unit->id,
                'total_price' => $data['total_price'],
                'down_payment_amount' => $data['down_payment_amount'],
                'down_payment_percentage' => $data['down_payment_percentage'],
                'expected_completion' => $data['expected_completion'],
            ]);

            foreach ($data['payment_plans'] as $plan) {
                PaymentPlan::create([
                    'construction_detail_id' => $constructionDetail->id,
                    'duration_years' => $plan['duration_years'],
                    'monthly_installment' => $plan['monthly_installment'],
                ]);
            }

            $this->attachRandomImages($unit);
        }
    }

    /**
     * Attach random images to a unit.
     */
    protected function attachRandomImages(Unit $unit, int $min = 2, int $max = 4): void
    {
        $imagesPath = public_path('images/properties');

        if (! is_dir($imagesPath)) {
            return;
        }

        $numberOfImages = rand($min, $max);
        $selectedImages = collect($this->propertyImages)
            ->shuffle()
            ->take($numberOfImages);

        foreach ($selectedImages as $image) {
            $imagePath = $imagesPath.'/'.$image;

            if (file_exists($imagePath)) {
                $unit->copyMedia($imagePath)
                    ->toMediaCollection('images');
            }
        }
    }
}
