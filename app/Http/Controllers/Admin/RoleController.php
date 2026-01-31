<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::all();

        return view('admin.roles.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created role in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $role->permissions()->sync($request->input('permissions', []));

            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create role.']);
        }
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Update the specified role in database.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('roles', 'slug')->ignore($role->id)],
            'description' => ['nullable', 'string'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $role->permissions()->sync($request->input('permissions', []));

            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update role.']);
        }
    }

    /**
     * Delete the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete role assigned to users.');
        }

        DB::beginTransaction();
        try {
            $role->permissions()->detach();
            $role->delete();
            DB::commit();
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.roles.index')->with('error', 'Failed to delete role.');
        }
    }
}
