<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Helpers\CartManagement;
use App\Livewire\Common\Navbar;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Cart Page - Ecom')]
class CartPage extends Component
{
    use LivewireAlert;
    public $cart_items = [];
    public $grand_total;

    public function mount() {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function removeItem( $product_id ) {
        $this->cart_items = CartManagement::removeItemFromCart($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count : count( $this->cart_items ))->to(Navbar::class);

        $this->alert('success', 'Product removed from the cart successfully!', [
            'position' => 'bottom-end', 
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function decreaseQty($product_id) {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function increaseQty($product_id) {
        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }


    public function render()
    {
        return view('livewire.cart-page');
    }
}
