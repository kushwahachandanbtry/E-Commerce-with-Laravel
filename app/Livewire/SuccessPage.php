<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Success Page - Ecom')]
class SuccessPage extends Component
{
    public function render()
    {
        return view('livewire.success-page');
    }
}
