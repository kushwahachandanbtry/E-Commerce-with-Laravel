<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $averagePrice = Order::query()->avg('grand_total') ?? 0;

        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Orders Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Orders Shipped', Order::query()->where('status', 'shipped')->count()),
            // Stat::make('Order Delivered', Order::query()->where('status', 'delivered')->count()),
            Stat::make('Average Price', Number::currency($averagePrice, 'INR')),
        ];
    }
}

