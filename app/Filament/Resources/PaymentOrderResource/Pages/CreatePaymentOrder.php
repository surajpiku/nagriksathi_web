<?php

namespace App\Filament\Resources\PaymentOrderResource\Pages;

use App\Filament\Resources\PaymentOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentOrder extends CreateRecord
{
    protected static string $resource = PaymentOrderResource::class;
}
