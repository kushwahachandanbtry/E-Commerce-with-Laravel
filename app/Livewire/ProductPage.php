<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Common\Navbar;
use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Title('Product Page - Ecom')]
class ProductPage extends Component
{
    use WithPagination;
    
    use LivewireAlert;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured = false;

    #[Url]
    public $onSale = false;

    #[Url]
    public $price_range = 300000;

    #[Url] 
    public $sort = 'latest';

    public function addToCart( $product_id ) {
        $total_count = CartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully!', [
            'position' => 'bottom-end', 
            'timer' => 3000,
            'toast' => true,
        ]);
    }
    public function render()
    {
        $productsQuery = Products::query()->where('is_active', 1);

        if (!empty($this->selected_categories)) {
            $productsQuery->whereIn('category_id', $this->selected_categories);
        }

        if (!empty($this->selected_brands)) {
            $productsQuery->whereIn('brand_id', $this->selected_brands);
        }
        
        // Filter for featured products
        if ($this->featured) {
            $productsQuery->where('is_featured', 1);
        }

        if( $this->onSale ) {
            $productsQuery->where('on_sale', 1);
        }

        if( $this->price_range ) {
            $productsQuery->whereBetween('price', [0, $this->price_range]);
        }

        if( $this->sort == 'latest' ) {
            $productsQuery->latest();
        }

        if( $this->sort == 'price' ) {
            $productsQuery->orderBy('price');
        }

        return view('livewire.product-page', [
            'products' => $productsQuery->paginate(6),
            'categories' => Categories::where('is_active', 1)->get(['id', 'name', 'slug']),
            'brands' => Brands::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
