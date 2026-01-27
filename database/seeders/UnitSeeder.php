<?php

namespace Database\Seeders;

use App\Models\ConstructionDetail;
use App\Models\PaymentPlan;
use App\Models\RentalDetail;
use App\Models\SaleDetail;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // Rental Units
        $rentalUnits = [
            [
                'title' => ['ar' => 'شقة فاخرة في الرياض', 'en' => 'Luxury Apartment in Riyadh'],
                'description' => ['ar' => 'شقة فاخرة مع إطلالة رائعة على المدينة', 'en' => 'Luxury apartment with amazing city view'],
                'location' => ['ar' => 'الرياض، حي النخيل', 'en' => 'Riyadh, Al Nakheel District'],
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area' => 180,
                'monthly_rent' => 8000,
                'insurance_amount' => 16000,
            ],
            [
                'title' => ['ar' => 'استوديو عصري', 'en' => 'Modern Studio'],
                'description' => ['ar' => 'استوديو عصري مفروش بالكامل', 'en' => 'Fully furnished modern studio'],
                'location' => ['ar' => 'جدة، حي الروضة', 'en' => 'Jeddah, Al Rawdah District'],
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
        }

        // Sale Units
        $saleUnits = [
            [
                'title' => ['ar' => 'فيلا راقية', 'en' => 'Elegant Villa'],
                'description' => ['ar' => 'فيلا راقية مع حديقة خاصة ومسبح', 'en' => 'Elegant villa with private garden and pool'],
                'location' => ['ar' => 'الدمام، حي الفيصلية', 'en' => 'Dammam, Al Faisaliah District'],
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area' => 450,
                'sale_price' => 2500000,
                'is_negotiable' => true,
            ],
            [
                'title' => ['ar' => 'شقة دوبلكس', 'en' => 'Duplex Apartment'],
                'description' => ['ar' => 'شقة دوبلكس واسعة بتشطيب فاخر', 'en' => 'Spacious duplex apartment with luxury finishing'],
                'location' => ['ar' => 'الخبر، حي اللؤلؤ', 'en' => 'Khobar, Pearl District'],
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
        }

        // Under Construction Units
        $constructionUnits = [
            [
                'title' => ['ar' => 'مشروع برج السماء', 'en' => 'Sky Tower Project'],
                'description' => ['ar' => 'وحدات سكنية فاخرة في برج السماء', 'en' => 'Luxury residential units in Sky Tower'],
                'location' => ['ar' => 'الرياض، طريق الملك فهد', 'en' => 'Riyadh, King Fahd Road'],
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
        }
    }
}
