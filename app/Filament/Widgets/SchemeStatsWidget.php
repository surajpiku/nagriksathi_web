<?php

namespace App\Filament\Widgets;

use App\Models\Scheme;
use App\Models\Opportunity;
use App\Models\User;
use App\Models\PaymentOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SchemeStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSchemes      = Scheme::count();
        $activeSchemes     = Scheme::where('is_active', true)->count();
        $centralSchemes    = Scheme::where('is_central', true)->count();
        $stateSchemes      = Scheme::where('is_central', false)->count();
        $totalOpps         = Opportunity::count();
        $activeOpps        = Opportunity::where('is_active', true)->count();
        $expiredOpps       = Opportunity::where('apply_end', '<', now()->toDateString())->where('is_active', true)->count();
        $totalUsers        = User::count();
        $paidUsers         = User::where('subscription_tier', '!=', 'free')->count();
        $revenueToday      = PaymentOrder::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');
        $revenueMonth      = PaymentOrder::where('status', 'paid')->whereMonth('paid_at', now()->month)->sum('amount');

        return [
            Stat::make('Total Schemes', number_format($totalSchemes))
                ->description("{$activeSchemes} active • {$centralSchemes} central • {$stateSchemes} state")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart([
                    Scheme::whereDate('created_at', now()->subDays(6))->count(),
                    Scheme::whereDate('created_at', now()->subDays(5))->count(),
                    Scheme::whereDate('created_at', now()->subDays(4))->count(),
                    Scheme::whereDate('created_at', now()->subDays(3))->count(),
                    Scheme::whereDate('created_at', now()->subDays(2))->count(),
                    Scheme::whereDate('created_at', now()->subDays(1))->count(),
                    Scheme::whereDate('created_at', now())->count(),
                ]),

            Stat::make('Government Jobs', number_format($totalOpps))
                ->description("{$activeOpps} active • {$expiredOpps} expired (need cleanup)")
                ->descriptionIcon('heroicon-m-briefcase')
                ->color($expiredOpps > 10 ? 'warning' : 'success'),

            Stat::make('Total Users', number_format($totalUsers))
                ->description("{$paidUsers} paid subscribers")
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Revenue Today', '₹' . number_format($revenueToday))
                ->description('₹' . number_format($revenueMonth) . ' this month')
                ->descriptionIcon('heroicon-m-currency-rupee')
                ->color('success'),
        ];
    }
}