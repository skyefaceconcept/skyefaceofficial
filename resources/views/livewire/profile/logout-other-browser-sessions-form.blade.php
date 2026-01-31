<div>
    <p class="text-muted mb-4">
        <i class="fa fa-globe mr-2"></i>
        Manage and log out your active sessions on other browsers and devices.
    </p>

    <div class="card mb-3">
        <div class="card-body">
            <p class="text-muted small">
                If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </p>

            <div class="mt-4">
                <h6 class="mb-3"><i class="fa fa-desktop mr-2"></i>Active Sessions</h6>

                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">
                                <strong>Your Current Browser Session</strong>
                            </p>
                            <small class="text-muted">
                                <i class="fa fa-check-circle mr-1 text-success"></i>
                                Active now
                            </small>
                        </div>
                        <span class="badge badge-success">ACTIVE</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="button" class="btn btn-warning" wire:click="$toggle('confirmingLogout')">
                    <i class="fa fa-sign-out mr-2"></i>Log Out Other Browser Sessions
                </button>
            </div>
        </div>
    </div>

    <!-- Log Out Other Browser Sessions Confirmation Modal -->
    @if ($confirmingLogout)
        <div class="card border-danger mt-4">
            <div class="card-header bg-light border-danger">
                <h5 class="card-title mb-0 text-danger">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Confirm Logout
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.
                </p>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        wire:model="password"
                        class="form-control"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >
                    @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4">
                    <button
                        type="button"
                        wire:click="$toggle('confirmingLogout')"
                        class="btn btn-secondary mr-2"
                    >
                        <i class="fa fa-times mr-2"></i>Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="logoutOtherBrowserSessions"
                        class="btn btn-danger"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            <i class="fa fa-sign-out mr-2"></i>Log Out Other Sessions
                        </span>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-spin mr-2"></i>Logging Out...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
