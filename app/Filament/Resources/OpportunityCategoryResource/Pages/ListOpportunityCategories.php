<?php

namespace App\Filament\Resources\OpportunityCategoryResource\Pages;

use App\Filament\Resources\OpportunityCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOpportunityCategories extends ListRecords
{
    protected static string $resource = OpportunityCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
