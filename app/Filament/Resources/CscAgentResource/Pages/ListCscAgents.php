<?php

namespace App\Filament\Resources\CscAgentResource\Pages;

use App\Filament\Resources\CscAgentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCscAgents extends ListRecords
{
    protected static string $resource = CscAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}