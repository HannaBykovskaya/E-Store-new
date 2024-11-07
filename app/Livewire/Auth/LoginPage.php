<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


#[Title('Login')]
class LoginPage extends Component
{

	public $email;
	public $password;

	//register user
	public function save()
	{
		$this->validate([
			'email' => 'required|email|max:255|exists:users,email',
			'password' => 'required|min:4|max:8',
		]);

			if(!auth()->attempt([
				'email' => $this->email,
				'password' => $this->password,
			])){
				session()->flash('error', 'Invalid credentials');
				return;
			}


			//redirect user
			redirect()->intended();
	}







	public function render()
	{
		return view('livewire.auth.login-page');
	}
}
