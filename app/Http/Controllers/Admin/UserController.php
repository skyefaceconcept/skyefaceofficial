<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('role')->paginate(15);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'fname' => ['required','string','max:255'],
            'mname' => ['nullable','string','max:255'],
            'lname' => ['required','string','max:255'],
            'dob' => ['nullable','date'],
            'phone' => ['nullable','string','max:20'],
            'username' => ['required','string','max:255', Rule::unique('users','username')],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => ['required','string','min:8','confirmed'],
            'role_id' => ['nullable','integer','exists:roles,id'],
            'photo' => ['nullable','image','mimes:jpg,jpeg,png','max:1024'],
        ]);

        $user = new User();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
            $first = $data['fname'] ?? $data['username'] ?? 'user';
            $slug = Str::slug(substr($first, 0, 20));
            $date = date('Ymd');
            $rand = Str::lower(Str::random(4));
            $filename = $slug . '_' . $date . '_' . $rand . '.' . $ext;
            $path = $file->storeAs('profile-photos', $filename, 'public');
            $user->profile_photo_path = $path;
        }

        $user->forceFill([
            'fname' => $data['fname'],
            'mname' => $data['mname'] ?? null,
            'lname' => $data['lname'],
            'dob' => $data['dob'] ?? null,
            'phone' => $data['phone'] ?? null,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'] ?? null,
        ])->save();

        // Send verification email if email is not verified
        if (empty($user->email_verified_at)) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', ['user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Build validation rules dynamically
            $rules = [
                'fname' => ['required','string','max:255'],
                'mname' => ['nullable','string','max:255'],
                'lname' => ['required','string','max:255'],
                'dob' => ['nullable','date'],
                'phone' => ['nullable','string','max:20'],
                'username' => ['required','string','max:255', Rule::unique('users','username')->ignore($user->id)],
                'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
                'role_id' => ['nullable','integer','exists:roles,id'],
                'photo' => ['nullable','image','mimes:jpg,jpeg,png','max:1024'],
            ];

            // Password validation - only if password is provided
            if ($request->filled('password')) {
                $rules['password'] = ['required','string','min:8','confirmed'];
            }

            $data = $request->validate($rules);

            if ($request->hasFile('photo')) {
                // delete old photo
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                $file = $request->file('photo');
                $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                $first = $data['fname'] ?? $user->fname ?? $user->name ?? 'user';
                $slug = Str::slug(substr($first, 0, 20));
                $date = date('Ymd');
                $rand = Str::lower(Str::random(4));
                $filename = $slug . '_' . $date . '_' . $rand . '.' . $ext;
                $path = $file->storeAs('profile-photos', $filename, 'public');
                $user->profile_photo_path = $path;
            }

            $originalEmail = $user->email;

            $user->forceFill([
                'fname' => $data['fname'],
                'mname' => $data['mname'] ?? null,
                'lname' => $data['lname'],
                'dob' => $data['dob'] ?? null,
                'phone' => $data['phone'] ?? null,
                'username' => $data['username'],
                'email' => $data['email'],
                'role_id' => $data['role_id'] ?? null,
            ])->save();

            // If email changed from original, reset verification and send email
            if ($originalEmail !== ($data['email'] ?? $originalEmail)) {
                $user->email_verified_at = null;
                $user->save();
                $user->sendEmailVerificationNotification();
            }

            // Update password only if provided and confirmed by validation
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
                $user->save();
            }

            return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error updating user: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user)
    {
        // To be implemented
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('role');
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Reset email verification and send verification email to the user (admin action).
     */
    public function resendVerification(User $user)
    {
        // Reset verification and send a new verification email
        $user->email_verified_at = null;
        $user->save();

        Mail::to($user->email)->send(new VerifyEmail($user));

        return redirect()->back()->with('success', 'Verification email sent to the user.');
    }
}
