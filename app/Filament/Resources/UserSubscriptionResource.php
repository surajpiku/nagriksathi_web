<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSubscriptionResource\Pages;
use App\Models\UserSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class UserSubscriptionResource extends Resource
{
    protected static ?string $model          = UserSubscription::class;
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Revenue';
    protected static ?string $navigationLabel = 'Subscriptions';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'phone')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('plan_id')
                ->relationship('plan', 'name')
                ->required(),
            Forms\Components\Select::make('billing_cycle')->options([
                'monthly' => 'Monthly',
                'yearly'  => 'Yearly',
            ])->required(),
            Forms\Components\Select::make('status')->options([
                'active'    => 'Active',
                'cancelled' => 'Cancelled',
                'expired'   => 'Expired',
                'trial'     => 'Trial',
            ])->required(),
            Forms\Components\DateTimePicker::make('starts_at')->required(),
            Forms\Components\DateTimePicker::make('ends_at')->required(),
            Forms\Components\Textarea::make('cancellation_reason')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('User Phone')->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')->badge(),
                Tables\Columns\TextColumn::make('billing_cycle')
                    ->badge()
                    ->color(fn($state) => $state === 'yearly' ? 'success' : 'info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'active'    => 'success',
                        'cancelled' => 'danger',
                        'expired'   => 'warning',
                        'trial'     => 'info',
                    }),
                Tables\Columns\TextColumn::make('starts_at')->dateTime('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('ends_at')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active'    => 'Active',
                    'cancelled' => 'Cancelled',
                    'expired'   => 'Expired',
                ]),
                Tables\Filters\SelectFilter::make('plan')->relationship('plan', 'name'),
            ])
            ->actions([
                // Extend subscription by 1 month
                Tables\Actions\Action::make('extend')
                    ->label('Extend 1 Month')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (UserSubscription $record) {
                        $record->update([
                            'ends_at' => $record->ends_at->addMonth(),
                            'status'  => 'active',
                        ]);
                        Notification::make()->title('Subscription extended by 1 month')->success()->send();
                    }),

                // Cancel subscription
                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'active')
                    ->requiresConfirmation()
                    ->action(function (UserSubscription $record) {
                        $record->update([
                            'status'       => 'cancelled',
                            'cancelled_at' => now(),
                        ]);
                        $record->user->update(['subscription_tier' => 'free']);
                        Notification::make()->title('Subscription cancelled')->danger()->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUserSubscriptions::route('/'),
            'create' => Pages\CreateUserSubscription::route('/create'),
            'edit'   => Pages\EditUserSubscription::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) UserSubscription::where('status', 'active')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}