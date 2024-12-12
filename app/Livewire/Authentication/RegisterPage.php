<?php

namespace App\Livewire\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Register Page - Ecom')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    //register users
    public function save() {
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|max:255'
        ]);

        //save to database 
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);

        //login user
        auth()->login($user);

        //redirect to home page
        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.authentication.register-page');
    }
}
