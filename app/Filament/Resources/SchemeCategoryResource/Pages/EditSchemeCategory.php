<?php

namespace App\Filament\Resources\SchemeCategoryResource\Pages;

use App\Filament\Resources\SchemeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchemeCategory extends EditRecord
{
    protected static string $resource = SchemeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
