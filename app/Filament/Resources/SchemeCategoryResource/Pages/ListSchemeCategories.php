<?php

namespace App\Filament\Resources\SchemeCategoryResource\Pages;

use App\Filament\Resources\SchemeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchemeCategories extends ListRecords
{
    protected static string $resource = SchemeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
