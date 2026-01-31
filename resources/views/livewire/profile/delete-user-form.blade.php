<div>
    <p class="text-muted mb-4">
        <i class="fa fa-trash mr-2"></i>
        Permanently delete your account and all associated data.
    </p>

    <div class="alert alert-danger">
        <strong><i class="fa fa-exclamation-triangle mr-2"></i>Warning:</strong> Once your account is deleted, there is no going back. Please be certain.
    </div>

    <button type="button" class="btn btn-danger" wire:click="$toggle('confirmingUserDeletion')">
        <i class="fa fa-trash mr-2"></i>Delete Account
    </button>

    <!-- Delete User Confirmation Modal -->
    @if ($confirmingUserDeletion)
        <div class="card border-danger mt-4">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0">
                    <i class="fa fa-exclamation-triangle mr-2"></i>Confirm Account Deletion
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    Are you sure you want to delete your account? Once your account is deleted, there is no going back.
                    Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="form-group">
                    <label for="delete_password">Password</label>
                    <input
                        type="password"
                        id="delete_password"
                        wire:model="password"
                        class="form-control"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >
                    @error('password')
                        <span class="text-danger small d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <button
                        type="button"
                        wire:click="$toggle('confirmingUserDeletion')"
                        class="btn btn-secondary mr-2"
                    >
                        <i class="fa fa-times mr-2"></i>Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="deleteUser"
                        class="btn btn-danger"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            <i class="fa fa-trash mr-2"></i>Delete Account
                        </span>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-spin mr-2"></i>Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
