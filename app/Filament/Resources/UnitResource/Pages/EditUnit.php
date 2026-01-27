<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\ConstructionDetail;
use App\Models\RentalDetail;
use App\Models\SaleDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditUnit extends EditRecord
{
    use Translatable;

    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        if ($record->rentalDetail) {
            $data['rentalDetail'] = $record->rentalDetail->toArray();
        }

        if ($record->saleDetail) {
            $data['saleDetail'] = $record->saleDetail->toArray();
        }

        if ($record->constructionDetail) {
            $data['constructionDetail'] = $record->constructionDetail->toArray();
            $data['constructionDetail']['paymentPlans'] = $record->constructionDetail->paymentPlans->toArray();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Handle rental details
        if ($record->type === 'rental') {
            if (isset($data['rentalDetail'])) {
                $record->rentalDetail()->updateOrCreate(
                    ['unit_id' => $record->id],
                    [
                        'monthly_rent' => $data['rentalDetail']['monthly_rent'],
                        'insurance_amount' => $data['rentalDetail']['insurance_amount'] ?? null,
                    ]
                );
            }
            // Clean up other detail types
            $record->saleDetail()->delete();
            $record->constructionDetail?->paymentPlans()->delete();
            $record->constructionDetail()->delete();
        }

        // Handle sale details
        if ($record->type === 'sale') {
            if (isset($data['saleDetail'])) {
                $record->saleDetail()->updateOrCreate(
                    ['unit_id' => $record->id],
                    [
                        'sale_price' => $data['saleDetail']['sale_price'],
                        'is_negotiable' => $data['saleDetail']['is_negotiable'] ?? false,
                    ]
                );
            }
            // Clean up other detail types
            $record->rentalDetail()->delete();
            $record->constructionDetail?->paymentPlans()->delete();
            $record->constructionDetail()->delete();
        }

        // Handle construction details
        if ($record->type === 'under_construction') {
            if (isset($data['constructionDetail'])) {
                $constructionDetail = $record->constructionDetail()->updateOrCreate(
                    ['unit_id' => $record->id],
                    [
                        'total_price' => $data['constructionDetail']['total_price'],
                        'down_payment_amount' => $data['constructionDetail']['down_payment_amount'] ?? null,
                        'down_payment_percentage' => $data['constructionDetail']['down_payment_percentage'] ?? null,
                        'expected_completion' => $data['constructionDetail']['expected_completion'] ?? null,
                    ]
                );

                // Sync payment plans
                $constructionDetail->paymentPlans()->delete();
                if (isset($data['constructionDetail']['paymentPlans'])) {
                    foreach ($data['constructionDetail']['paymentPlans'] as $plan) {
                        $constructionDetail->paymentPlans()->create([
                            'duration_years' => $plan['duration_years'],
                            'monthly_installment' => $plan['monthly_installment'],
                        ]);
                    }
                }
            }
            // Clean up other detail types
            $record->rentalDetail()->delete();
            $record->saleDetail()->delete();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
