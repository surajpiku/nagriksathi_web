<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentOrderResource\Pages;
use App\Models\PaymentOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentOrderResource extends Resource
{
    protected static ?string $model          = PaymentOrder::class;
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Revenue';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('razorpay_order_id')->disabled(),
            Forms\Components\TextInput::make('razorpay_payment_id')->disabled(),
            Forms\Components\TextInput::make('amount')->disabled(),
            Forms\Components\Select::make('status')->options([
                'created'  => 'Created',
                'paid'     => 'Paid',
                'failed'   => 'Failed',
                'refunded' => 'Refunded',
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')->searchable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')->badge(),
                Tables\Columns\TextColumn::make('razorpay_order_id')
                    ->label('Order ID')->copyable()->limit(20),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->badge()
                    ->color(fn($state) => $state === 'yearly' ? 'success' : 'info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'paid'     => 'success',
                        'created'  => 'warning',
                        'failed'   => 'danger',
                        'refunded' => 'info',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'paid'    => 'Paid',
                    'created' => 'Pending',
                    'failed'  => 'Failed',
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentOrders::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $revenue = PaymentOrder::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
        return $revenue > 0 ? '₹' . number_format($revenue) : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function canCreate(): bool { return false; }
}