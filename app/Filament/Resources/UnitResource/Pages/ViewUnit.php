<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

class ViewUnit extends ViewRecord
{
    use Translatable;

    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
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
}
