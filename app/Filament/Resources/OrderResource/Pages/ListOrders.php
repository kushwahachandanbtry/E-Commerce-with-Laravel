<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array{
        return [
            OrderStats::class
        ];
    }

    // protected function getFooterWidgets(): array{
    //     return [
    //         OrderStats::class
    //     ];
    // }





    // using for tabs but in this code have some bugs i should fix this bugs latter
    // public function getTabs(): array{
    //     return [
    //         null => Tab::make('All'),
    //         'new' => Tab::make('ds')->query(fn($query) => $query->where('status', 'new')),
    //         'delivered' => Tab::make('ds')->query(fn($query) => $query->where('status', 'delivered')),
    //         'shipped' => Tab::make('ds')->query(fn($query) => $query->where('status', 'shipped')),
    //         'processing' => Tab::make('ds')->query(fn($query) => $query->where('status', 'processing')),
    //         'cancelled' => Tab::make('ds')->query(fn($query) => $query->where('status', 'cancelled')),
            
    //     ];
    // }
}
