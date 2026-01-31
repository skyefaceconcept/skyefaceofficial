<div>
    <div>
    {{-- debug removed --}}

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fa fa-info-circle mr-2"></i>{{ session('info') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Existing values shown inside inputs via value attributes below --}}

    <form wire:submit.prevent="updateProfileInformation" enctype="multipart/form-data">
        <div class="form-group">
            <label for="photo" class="form-label"><i class="fa fa-camera mr-2 text-info"></i>Update Profile Photo</label>
            <div class="custom-file">
                <input type="file" wire:model="photo" id="photo" class="form-control-file" accept="image/*">
                <small class="form-text text-muted d-block mt-2">Accepted formats: JPEG, PNG, JPG, GIF (Max: 1MB)</small>
            </div>
            @if($photo)
                <div class="mt-3">
                    <small class="text-success"><i class="fa fa-check mr-1"></i>Photo selected</small>
                </div>
            @endif
            @error('photo') <span class="text-danger small d-block mt-2"><i class="fa fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror
        </div>

        <hr>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="fname">First Name <span class="text-danger">*</span></label>
                <input type="text" id="fname" wire:model="fname" class="form-control">
                @error('fname') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="mname">Middle Name</label>
                <input type="text" id="mname" wire:model="mname" class="form-control">
                @error('mname') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="lname">Last Name <span class="text-danger">*</span></label>
                <input type="text" id="lname" wire:model="lname" class="form-control">
                @error('lname') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" wire:model="dob" class="form-control">
                @error('dob') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="phone">Phone</label>
                <input type="text" id="phone" wire:model="phone" class="form-control">
                @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="form-group col-md-4">
                <label for="username">Username</label>
                <input type="text" id="username" wire:model="username" class="form-control">
                @error('username') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" id="email" wire:model="email" class="form-control" required>
            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove><i class="fa fa-save mr-2"></i>Save Changes</span>
            <span wire:loading><i class="fa fa-spinner fa-spin mr-2"></i>Saving...</span>
        </button>
        <button type="button" class="btn btn-secondary ml-2" wire:click="resetForm" title="Reset form to original values">
            <i class="fa fa-undo mr-2"></i>Reset
        </button>
    </form>
    </div>
    {{-- Removed Jetstream x-form-section duplication; using custom form above --}}
</div>
