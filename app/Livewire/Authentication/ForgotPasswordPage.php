<?php

namespace App\Livewire\Authentication;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Forgot Password Page - Ecom')]
class ForgotPasswordPage extends Component
{
    public $email;
    public function save() {
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email'
        ]);

        $status = Password::sendResetLink(['email' => $this->email ]);

        if( $status === Password::RESET_LINK_SENT ) {
            session()->flash('success', 'Password reset link has been sent to your email address!');
            $this->email = '';
        }
    }
    public function render()
    {
        return view('livewire.authentication.forgot-password-page');
    }
}
