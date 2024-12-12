<?php

namespace App\Livewire;

use App\Models\Categories;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Categories Page - Ecom')]
class CategoriesPage extends Component
{
    public function render()
    {

        $categories = Categories::where('is_active', 1)->get();
        return view('livewire.categories-page', [
            'categories' => $categories
        ]);
    }
}
