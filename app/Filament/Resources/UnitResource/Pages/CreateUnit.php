<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\ConstructionDetail;
use App\Models\RentalDetail;
use App\Models\SaleDetail;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateUnit extends CreateRecord
{
    use Translatable;

    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        if ($record->type === 'rental' && isset($data['rentalDetail'])) {
            RentalDetail::create([
                'unit_id' => $record->id,
                'monthly_rent' => $this->cleanMoney($data['rentalDetail']['monthly_rent']),
                'insurance_amount' => $this->cleanMoney($data['rentalDetail']['insurance_amount'] ?? null),
            ]);
        }

        if ($record->type === 'sale' && isset($data['saleDetail'])) {
            SaleDetail::create([
                'unit_id' => $record->id,
                'sale_price' => $this->cleanMoney($data['saleDetail']['sale_price']),
                'is_negotiable' => $data['saleDetail']['is_negotiable'] ?? false,
            ]);
        }

        if ($record->type === 'under_construction' && isset($data['constructionDetail'])) {
            $constructionDetail = ConstructionDetail::create([
                'unit_id' => $record->id,
                'total_price' => $this->cleanMoney($data['constructionDetail']['total_price']),
                'down_payment_amount' => $this->cleanMoney($data['constructionDetail']['down_payment_amount'] ?? null),
                'down_payment_percentage' => $data['constructionDetail']['down_payment_percentage'] ?? null,
                'expected_completion' => $data['constructionDetail']['expected_completion'] ?? null,
            ]);

            if (isset($data['constructionDetail']['paymentPlans'])) {
                foreach ($data['constructionDetail']['paymentPlans'] as $plan) {
                    $constructionDetail->paymentPlans()->create([
                        'duration_years' => $plan['duration_years'],
                        'monthly_installment' => $this->cleanMoney($plan['monthly_installment']),
                    ]);
                }
            }
        }
    }

    private function cleanMoney(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return str_replace(',', '', (string) $value);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
