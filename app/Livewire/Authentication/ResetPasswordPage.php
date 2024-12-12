<?php

namespace App\Livewire\Authentication;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Title('Reset Password Page - Ecom')]
class ResetPasswordPage extends Component
{
    #[Url]
    public $email;
    public $token;
    public $password;
    public $password_confirmation;

    public function mount( $token ) {
        $this->token = $token;
    }
    public function save() {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token
            ],
            function(User $user, string $password) {
                $password = $this->password;
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET?redirect('/login'):session()->flash('error', 'Something went wrong!');
    }
    public function render()
    {
        return view('livewire.authentication.reset-password-page');
    }
}
