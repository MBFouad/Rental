<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name' => ['ar' => 'القاهرة', 'en' => 'Cairo'],
                'slug' => 'cairo',
                'sort_order' => 1,
                'areas' => [
                    ['ar' => 'المعادي', 'en' => 'Maadi'],
                    ['ar' => 'مدينة نصر', 'en' => 'Nasr City'],
                    ['ar' => 'مصر الجديدة', 'en' => 'Heliopolis'],
                    ['ar' => 'القاهرة الجديدة', 'en' => 'New Cairo'],
                    ['ar' => 'التجمع الخامس', 'en' => 'Fifth Settlement'],
                    ['ar' => 'الرحاب', 'en' => 'El Rehab'],
                    ['ar' => 'مدينتي', 'en' => 'Madinaty'],
                    ['ar' => 'العبور', 'en' => 'Obour City'],
                    ['ar' => 'الشروق', 'en' => 'El Shorouk'],
                    ['ar' => 'بدر', 'en' => 'Badr City'],
                    ['ar' => 'السادس من أكتوبر', 'en' => '6th of October'],
                    ['ar' => 'الشيخ زايد', 'en' => 'Sheikh Zayed'],
                    ['ar' => 'الزمالك', 'en' => 'Zamalek'],
                    ['ar' => 'الدقي', 'en' => 'Dokki'],
                    ['ar' => 'المهندسين', 'en' => 'Mohandessin'],
                    ['ar' => 'العجوزة', 'en' => 'Agouza'],
                    ['ar' => 'الجيزة', 'en' => 'Giza'],
                    ['ar' => 'حدائق الأهرام', 'en' => 'Hadayek Al Ahram'],
                    ['ar' => 'فيصل', 'en' => 'Faisal'],
                    ['ar' => 'الهرم', 'en' => 'Haram'],
                    ['ar' => 'شبرا', 'en' => 'Shoubra'],
                    ['ar' => 'عين شمس', 'en' => 'Ain Shams'],
                    ['ar' => 'حدائق القبة', 'en' => 'Hadayek El Kobba'],
                    ['ar' => 'مدينة السلام', 'en' => 'Madinet El Salam'],
                    ['ar' => 'المقطم', 'en' => 'Mokattam'],
                    ['ar' => 'وسط البلد', 'en' => 'Downtown'],
                    ['ar' => 'جاردن سيتي', 'en' => 'Garden City'],
                    ['ar' => 'المنيل', 'en' => 'El Manial'],
                    ['ar' => 'الروضة', 'en' => 'El Rawda'],
                ],
            ],
            [
                'name' => ['ar' => 'الإسكندرية', 'en' => 'Alexandria'],
                'slug' => 'alexandria',
                'sort_order' => 2,
                'areas' => [
                    ['ar' => 'سموحة', 'en' => 'Smouha'],
                    ['ar' => 'سيدي جابر', 'en' => 'Sidi Gaber'],
                    ['ar' => 'لوران', 'en' => 'Laurent'],
                    ['ar' => 'رشدي', 'en' => 'Rushdy'],
                    ['ar' => 'ستانلي', 'en' => 'Stanley'],
                    ['ar' => 'جليم', 'en' => 'Gleem'],
                    ['ar' => 'سان ستيفانو', 'en' => 'San Stefano'],
                    ['ar' => 'المنتزه', 'en' => 'Montaza'],
                    ['ar' => 'ميامي', 'en' => 'Miami'],
                    ['ar' => 'العجمي', 'en' => 'Agami'],
                ],
            ],
            [
                'name' => ['ar' => 'العين السخنة', 'en' => 'Ain Sokhna'],
                'slug' => 'ain-sokhna',
                'sort_order' => 3,
                'areas' => [
                    ['ar' => 'بورتو السخنة', 'en' => 'Porto Sokhna'],
                    ['ar' => 'لاسيرينا', 'en' => 'La Sirena'],
                    ['ar' => 'أزها', 'en' => 'Azha'],
                    ['ar' => 'بو ساندس', 'en' => 'Bo Sands'],
                    ['ar' => 'العين باي', 'en' => 'El Ain Bay'],
                ],
            ],
            [
                'name' => ['ar' => 'الساحل الشمالي', 'en' => 'North Coast'],
                'slug' => 'north-coast',
                'sort_order' => 4,
                'areas' => [
                    ['ar' => 'مراسي', 'en' => 'Marassi'],
                    ['ar' => 'هاسيندا', 'en' => 'Hacienda'],
                    ['ar' => 'بو آيلاند', 'en' => 'Bo Islands'],
                    ['ar' => 'سيدي عبد الرحمن', 'en' => 'Sidi Abdel Rahman'],
                    ['ar' => 'العلمين الجديدة', 'en' => 'New Alamein'],
                ],
            ],
        ];

        foreach ($cities as $cityData) {
            $city = City::updateOrCreate(
                ['slug' => $cityData['slug']],
                [
                    'name' => $cityData['name'],
                    'is_active' => true,
                    'sort_order' => $cityData['sort_order'],
                ]
            );

            foreach ($cityData['areas'] as $index => $areaName) {
                $slug = Str::slug($areaName['en']);
                Area::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'city_id' => $city->id,
                        'name' => $areaName,
                        'is_active' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }
    }
}
