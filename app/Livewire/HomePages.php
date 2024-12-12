<?php

namespace App\Livewire;

use App\Models\Brands;
use App\Models\Categories;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Ecom')]
class HomePages extends Component
{
    public function render()
    {

        $brands = Brands::where('is_active', 1)->get();
        $categories = Categories::where('is_active', 1)->get();
        // dd($brands);
        return view('livewire.home-pages',
        [
            'brands' => $brands,
            'categories' => $categories
        ]);
    }
}
