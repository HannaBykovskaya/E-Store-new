<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;


#[Title('Reset password')]
class ResetPasswordPage extends Component
{

	public $token;
	#[Url]
	public $email;
	public $password;
	public $password_confirmation;

	public function mount($token)
	{
		$this->token = $token;
	}

	//register user
	public function save()
	{
		$this->validate([
			'token' => 'required',
			'email' => 'required|email|max:255|exists:users,email',
			'password' => 'required|min:6|confirmed',
		]);

		$status = Password::reset([
			'email' => $this->email,
			'password' => $this->password,
			'password_confirmation' => $this->password_confirmation,
			'token' => $this->token,
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

	return $status === Password::PASSWORD_RESET?redirect('/login'):session->flash('error',
		'Something went wrong');


	}

	public function render()
	{
		return view('livewire.auth.reset-password-page');
	}
}
