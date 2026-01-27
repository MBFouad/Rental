<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\ConstructionDetail;
use App\Models\RentalDetail;
use App\Models\SaleDetail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateUnit extends CreateRecord
{
    use Translatable;

    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        if ($record->type === 'rental' && isset($data['rentalDetail'])) {
            RentalDetail::create([
                'unit_id' => $record->id,
                'monthly_rent' => $data['rentalDetail']['monthly_rent'],
                'insurance_amount' => $data['rentalDetail']['insurance_amount'] ?? null,
            ]);
        }

        if ($record->type === 'sale' && isset($data['saleDetail'])) {
            SaleDetail::create([
                'unit_id' => $record->id,
                'sale_price' => $data['saleDetail']['sale_price'],
                'is_negotiable' => $data['saleDetail']['is_negotiable'] ?? false,
            ]);
        }

        if ($record->type === 'under_construction' && isset($data['constructionDetail'])) {
            $constructionDetail = ConstructionDetail::create([
                'unit_id' => $record->id,
                'total_price' => $data['constructionDetail']['total_price'],
                'down_payment_amount' => $data['constructionDetail']['down_payment_amount'] ?? null,
                'down_payment_percentage' => $data['constructionDetail']['down_payment_percentage'] ?? null,
                'expected_completion' => $data['constructionDetail']['expected_completion'] ?? null,
            ]);

            if (isset($data['constructionDetail']['paymentPlans'])) {
                foreach ($data['constructionDetail']['paymentPlans'] as $plan) {
                    $constructionDetail->paymentPlans()->create([
                        'duration_years' => $plan['duration_years'],
                        'monthly_installment' => $plan['monthly_installment'],
                    ]);
                }
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
