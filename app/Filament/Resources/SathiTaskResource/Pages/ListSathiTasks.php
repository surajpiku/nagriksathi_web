<?php

namespace App\Filament\Resources\SathiTaskResource\Pages;

use App\Filament\Resources\SathiTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSathiTasks extends ListRecords
{
    protected static string $resource = SathiTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
