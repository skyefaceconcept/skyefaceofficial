<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordForm extends Component
{
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public function mount()
    {
        $this->state = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];
    }

    protected function rules()
    {
        return [
            'state.current_password' => ['required', 'string', 'current_password'],
            'state.password' => ['required', 'string', 'min:8', 'confirmed'],
            'state.password_confirmation' => ['required', 'string', 'min:8'],
        ];
    }

    public function updatePassword()
    {
        try {
            $this->validate();

            Auth::user()->forceFill([
                'password' => Hash::make($this->state['password']),
            ])->save();

            $this->reset();

            $this->emit('saved');
            $this->dispatchBrowserEvent('password-updated', [
                'message' => 'Password updated successfully!'
            ]);
        } catch (\Exception $e) {
            $this->addError('password', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.profile.update-password-form');
    }
}
