<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Order Details Page - Ecom')]
class MyOrdersDetailPage extends Component
{
    public function render()
    {
        return view('livewire.my-orders-detail-page');
    }
}
