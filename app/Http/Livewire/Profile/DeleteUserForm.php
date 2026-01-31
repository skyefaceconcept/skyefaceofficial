<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeleteUserForm extends Component
{
    public $password;
    public $confirmingUserDeletion = false;

    public function rules()
    {
        return [
            'password' => 'required|current_password',
        ];
    }

    public function render()
    {
        return view('livewire.profile.delete-user-form');
    }

    public function deleteUser()
    {
        $this->validate();

        $user = Auth::user();
        $user->delete();

        Auth::logout();

        return redirect('/');
    }
}
