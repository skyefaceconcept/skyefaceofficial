<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            // delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $ext = strtolower($input['photo']->getClientOriginalExtension() ?: $input['photo']->extension());
            $first = $user->fname ?? $user->name ?? 'user';
            $slug = Str::slug(substr($first, 0, 20));
            $date = date('Ymd');
            $rand = Str::lower(Str::random(4));
            $filename = $slug . '_' . $date . '_' . $rand . '.' . $ext;

            $path = $input['photo']->storeAs('profile-photos', $filename, 'public');
            $user->profile_photo_path = $path;
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'fname' => $input['fname'],
                'mname' => $input['mname'] ?? null,
                'lname' => $input['lname'],
                'dob' => $input['dob'] ?? null,
                'phone' => $input['phone'] ?? null,
                'username' => $input['username'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'fname' => $input['fname'],
            'mname' => $input['mname'] ?? null,
            'lname' => $input['lname'],
            'dob' => $input['dob'] ?? null,
            'phone' => $input['phone'] ?? null,
            'username' => $input['username'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
