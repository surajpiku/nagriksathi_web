<?php

namespace App\Filament\Resources\CscAgentResource\Pages;

use App\Filament\Resources\CscAgentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCscAgent extends EditRecord
{
    protected static string $resource = CscAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}