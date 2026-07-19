<?php

namespace App\Filament\Resources\SchemeResource\Pages;

use App\Filament\Resources\SchemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchemes extends ListRecords
{
    protected static string $resource = SchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
