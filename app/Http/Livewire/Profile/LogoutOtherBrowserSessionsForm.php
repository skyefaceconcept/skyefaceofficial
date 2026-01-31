<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LogoutOtherBrowserSessionsForm extends Component
{
    public $password;
    public $confirmingLogout = false;

    public function render()
    {
        return view('livewire.profile.logout-other-browser-sessions-form');
    }

    protected function rules()
    {
        return [
            'password' => 'required|current_password:web',
        ];
    }

    public function logoutOtherBrowserSessions()
    {
        $this->validate();

        Auth::logoutOtherDevices($this->password);

        $this->confirmingLogout = false;
        $this->password = '';

        session()->flash('status', 'Browser sessions logged out successfully.');
    }
}
