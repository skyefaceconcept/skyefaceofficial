<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TwoFactorAuthenticationForm extends Component
{
    public $showingQrCode = false;
    public $showingRecoveryCodes = false;
    public $confirmingDisableTwoFactorAuthentication = false;

    public function render()
    {
        return view('livewire.profile.two-factor-authentication-form');
    }

    public function enableTwoFactorAuthentication()
    {
        $user = Auth::user();

        if (empty($user->two_factor_secret)) {
            $user->forceFill([
                'two_factor_secret' => encrypt(Str::random(32)),
            ])->save();
        }

        $this->showingQrCode = true;
    }

    public function confirmTwoFactorAuthentication()
    {
        $user = Auth::user();

        if (empty($user->two_factor_secret)) {
            return;
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => json_encode(
                collect(range(1, 8))
                    ->map(fn () => Str::random(10))
                    ->toArray()
            ),
        ])->save();

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = true;

        session()->flash('status', 'two-factor-authentication-enabled');
    }

    public function disableTwoFactorAuthentication()
    {
        $user = Auth::user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->confirmingDisableTwoFactorAuthentication = false;
        $this->showingRecoveryCodes = false;

        session()->flash('status', 'two-factor-authentication-disabled');
    }
}
