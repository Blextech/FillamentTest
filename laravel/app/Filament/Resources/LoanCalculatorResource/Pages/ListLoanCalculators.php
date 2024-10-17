<?php

namespace App\Filament\Resources\LoanCalculatorResource\Pages;

use App\Filament\Resources\LoanCalculatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanCalculators extends ListRecords
{
    protected static string $resource = LoanCalculatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
