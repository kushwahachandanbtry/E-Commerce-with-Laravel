<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Common\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\Products;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Product Details Page - Ecom')]
class ProductDetailsPage extends Component
{
    use LivewireAlert;

    public $slug;

    public $quantity = 1;

    public function mount( $slug ) {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.product-details-page',[
            'product' => Products::where('slug', $this->slug )->firstOrFail(),
        ]);
    }

    public function increaseQty() {
        $this->quantity++;
    }

    public function decreaseQty() {
        if( $this->quantity > 1 ){
            $this->quantity--;
        }
    }
    public function addToCart( $product_id ) {
        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully!', [
            'position' => 'bottom-end', 
            'timer' => 3000,
            'toast' => true,
        ]);
    }
}
