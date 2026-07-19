<?php

namespace App\Filament\Resources\OpportunityCategoryResource\Pages;

use App\Filament\Resources\OpportunityCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpportunityCategory extends EditRecord
{
    protected static string $resource = OpportunityCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
