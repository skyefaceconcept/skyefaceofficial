<div>
    @if (session('status') == 'password-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>Password updated successfully!
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <p class="text-muted mb-4">
        <i class="fa fa-lock mr-2"></i>
        Ensure your account is using a long, random password to stay secure.
    </p>

    <form wire:submit.prevent="updatePassword">
        <div class="form-group">
            <label for="current_password">Current Password <span class="text-danger">*</span></label>
            <input
                type="password"
                id="current_password"
                wire:model="state.current_password"
                class="form-control"
                placeholder="Enter your current password"
                autocomplete="current-password"
            >
            @error('state.current_password')
                <span class="text-danger small d-block mt-2">
                    <i class="fa fa-exclamation-circle mr-1"></i>{{ $message }}
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">New Password <span class="text-danger">*</span></label>
            <input
                type="password"
                id="password"
                wire:model="state.password"
                class="form-control"
                placeholder="Enter your new password (min 8 characters)"
                autocomplete="new-password"
            >
            @error('state.password')
                <span class="text-danger small d-block mt-2">
                    <i class="fa fa-exclamation-circle mr-1"></i>{{ $message }}
                </span>
            @enderror
            <small class="form-text text-muted d-block mt-2">At least 8 characters</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
            <input
                type="password"
                id="password_confirmation"
                wire:model="state.password_confirmation"
                class="form-control"
                placeholder="Confirm your new password"
                autocomplete="new-password"
            >
            @error('state.password_confirmation')
                <span class="text-danger small d-block mt-2">
                    <i class="fa fa-exclamation-circle mr-1"></i>{{ $message }}
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove><i class="fa fa-save mr-2"></i>Update Password</span>
            <span wire:loading><i class="fa fa-spinner fa-spin mr-2"></i>Updating...</span>
        </button>
    </form>
</div>
