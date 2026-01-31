<div>
    @if (session('status') == 'two-factor-authentication-enabled')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>Two-factor authentication enabled successfully!
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if (session('status') == 'two-factor-authentication-disabled')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fa fa-info-circle mr-2"></i>Two-factor authentication disabled.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="mb-4">
        <p class="text-muted">
            <i class="fa fa-lock mr-2 text-warning"></i>
            Add an extra layer of security to your account using two-factor authentication.
        </p>
    </div>

    @if (! auth()->user()->two_factor_enabled)
        <button type="button" wire:click="enableTwoFactorAuthentication" class="btn btn-primary">
            <i class="fa fa-shield mr-2"></i>Enable Two-Factor Authentication
        </button>
    @else
        <div class="alert alert-info">
            <p class="mb-0">
                <i class="fa fa-check-circle mr-2 text-success"></i>
                <strong>Two-factor authentication is enabled.</strong>
            </p>
        </div>

        @if ($showingRecoveryCodes)
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa fa-key mr-2"></i>Save Recovery Codes</h6>
                    <p class="text-muted small">Store these codes in a secure place. You can use them to access your account if you lose access to your authentication app.</p>
                    <div class="bg-light p-3 rounded">
                        <code style="word-break: break-all;">{{ json_encode(auth()->user()->recoveryCodes(), JSON_PRETTY_PRINT) }}</code>
                    </div>
                </div>
            </div>
        @elseif ($showingQrCode)
            <div class="card mt-4">
                <div class="card-body text-center">
                    <h6 class="card-title mb-3"><i class="fa fa-qrcode mr-2"></i>Scan QR Code</h6>
                    <p class="text-muted small">Scan this QR code with your authenticator app.</p>
                    <div class="mb-3">
                        {!! auth()->user()->twoFactorQrCodeSvg() !!}
                    </div>
                    <button type="button" wire:click="confirmTwoFactorAuthentication" class="btn btn-success">
                        <i class="fa fa-check mr-2"></i>Confirm
                    </button>
                </div>
            </div>
        @endif

        <button type="button" wire:click="$toggle('confirmingDisableTwoFactorAuthentication')" class="btn btn-danger mt-3">
            <i class="fa fa-times mr-2"></i>Disable Two-Factor Authentication
        </button>

        @if ($confirmingDisableTwoFactorAuthentication)
            <div class="alert alert-warning mt-3">
                <p class="mb-0">
                    <strong>Are you sure?</strong> This will disable two-factor authentication.
                </p>
                <div class="mt-3">
                    <button type="button" wire:click="disableTwoFactorAuthentication" class="btn btn-sm btn-danger">
                        <i class="fa fa-check mr-1"></i>Yes, Disable
                    </button>
                    <button type="button" wire:click="$toggle('confirmingDisableTwoFactorAuthentication')" class="btn btn-sm btn-secondary">
                        <i class="fa fa-times mr-1"></i>Cancel
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
