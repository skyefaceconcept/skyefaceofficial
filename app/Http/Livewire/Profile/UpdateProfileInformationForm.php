<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $photo;
    public $fname = '';
    public $mname = '';
    public $lname = '';
    public $dob = '';
    public $phone = '';
    public $username = '';
    public $email = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->fname = $user->fname;
            $this->mname = $user->mname;
            $this->lname = $user->lname;
            $this->dob = $user->dob;
            $this->phone = $user->phone;
            $this->username = $user->username;
            $this->email = $user->email;
        }
    }

    protected function rules()
    {
        return [
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'username' => ['nullable','string','max:50', Rule::unique('users','username')->ignore(Auth::id())],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore(Auth::id())],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ];
    }

    public function updateProfileInformation()
    {
        Log::info('Profile update started', ['user_id' => Auth::id()]);

        try {
            $validated = $this->validate();
            Log::info('Validation passed', ['validated' => $validated]);

            $user = Auth::user();

            // Prepare data to save
            $dataToSave = [
                'fname' => $this->fname,
                'mname' => $this->mname,
                'lname' => $this->lname,
                'dob' => $this->dob,
                'phone' => $this->phone,
                'username' => $this->username,
                'email' => $this->email,
            ];

            // Handle photo upload
            if ($this->photo) {
                Log::info('Photo file detected', ['size' => $this->photo->getSize()]);

                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                    Log::info('Old photo deleted');
                }

                $ext = strtolower($this->photo->getClientOriginalExtension());
                $filename = 'profile_' . Auth::id() . '_' . time() . '.' . $ext;
                $path = $this->photo->storeAs('profile-photos', $filename, 'public');
                $dataToSave['profile_photo_path'] = $path;
                Log::info('New photo uploaded', ['path' => $path]);
            }

            Log::info('About to update user', ['user_id' => $user->id, 'data' => $dataToSave]);

            // Update the user record
            $user->update($dataToSave);

            Log::info('User updated successfully', ['user_id' => $user->id]);

            // Reload user data
            $user = Auth::user();
            $this->fname = $user->fname;
            $this->mname = $user->mname;
            $this->lname = $user->lname;
            $this->dob = $user->dob;
            $this->phone = $user->phone;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->photo = null;

            $this->emit('saved');
            session()->flash('success', 'Profile updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $user = Auth::user();
        if ($user) {
            $this->fname = $user->fname;
            $this->mname = $user->mname;
            $this->lname = $user->lname;
            $this->dob = $user->dob;
            $this->phone = $user->phone;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->photo = null;
        }
        session()->flash('info', 'Form reset to original values.');
    }

    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
