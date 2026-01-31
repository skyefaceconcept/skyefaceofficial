<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::all();

        return view('admin.permissions.index', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        // Collect named routes (prefer admin.* routes) to let user link permission to a route
        $routes = collect(Route::getRoutes())->map(function ($r) {
            return [
                'name' => $r->getName(),
                'uri' => $r->uri(),
            ];
        })->filter(function ($r) {
            return ! empty($r['name']);
        })->values();

        // Prefer admin.* routes first
        $adminRoutes = $routes->filter(function ($r) {
            return str_starts_with($r['name'], 'admin.');
        })->values();

        $otherRoutes = $routes->filter(function ($r) {
            return ! str_starts_with($r['name'], 'admin.');
        })->values();

        $all = $adminRoutes->merge($otherRoutes);

        return view('admin.permissions.create', ['routes' => $all]);
    }

    /**
     * Store a newly created permission in database.
     */
    public function store()
    {
        $data = request()->validate([
            'route' => ['required', 'string'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', 'unique:permissions,slug'],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        // Derive slug and name from route to ensure consistency
        $routeName = $data['route'];
        $derived = Str::of($routeName)->replaceFirst('admin.', '')->replace('.', '_')->lower()->__toString();
        $name = $data['name'] ?? Str::of($derived)->replace(['_','-'], ' ')->title();
        $slug = $data['slug'] ?? $derived;
        $description = $data['description'] ?? 'Permission for route: ' . $routeName;

        DB::beginTransaction();
        try {
            Permission::create([
                'route' => $routeName,
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
            ]);

            DB::commit();

            return redirect()->route('admin.permissions.index')->with('success', 'Permission created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create permission.']);
        }
    }

    /**
     * Show the form for editing a permission.
     */
    public function edit(Permission $permission)
    {
        $routes = collect(Route::getRoutes())->map(function ($r) {
            return [
                'name' => $r->getName(),
                'uri' => $r->uri(),
            ];
        })->filter(function ($r) {
            return ! empty($r['name']);
        })->values();

        $adminRoutes = $routes->filter(function ($r) {
            return str_starts_with($r['name'], 'admin.');
        })->values();

        $otherRoutes = $routes->filter(function ($r) {
            return ! str_starts_with($r['name'], 'admin.');
        })->values();

        $all = $adminRoutes->merge($otherRoutes);

        return view('admin.permissions.edit', ['permission' => $permission, 'routes' => $all]);
    }

    /**
     * Update the specified permission in database.
     */
    public function update(Permission $permission)
    {
        $data = request()->validate([
            'route' => ['required', 'string'],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', 'unique:permissions,slug,' . $permission->id],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        $routeName = $data['route'];
        $derived = Str::of($routeName)->replaceFirst('admin.', '')->replace('.', '_')->lower()->__toString();
        $name = $data['name'] ?? Str::of($derived)->replace(['_','-'], ' ')->title();
        $slug = $data['slug'] ?? $derived;
        $description = $data['description'] ?? 'Permission for route: ' . $routeName;

        DB::beginTransaction();
        try {
            $permission->update([
                'route' => $routeName,
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
            ]);

            DB::commit();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update permission.']);
        }
    }

    /**
     * Delete the specified permission.
     */
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted.');
        } catch (\Exception $e) {
            return redirect()->route('admin.permissions.index')->with('error', 'Failed to delete permission.');
        }
    }

    /**
     * Generate permission metadata from a selected route (AJAX endpoint).
     */
    public function generateFromRoute(Request $request)
    {
        $route = $request->input('route');

        if (! $route) {
            return response()->json(['error' => 'Route is required.'], 400);
        }

        try {
            // Derive slug from route name
            $derived = Str::of($route)->replaceFirst('admin.', '')->replace('.', '_')->lower()->__toString();

            // Generate human-readable name
            $name = Str::of($derived)->replace(['_', '-'], ' ')->title();

            // Set slug (same as derived)
            $slug = $derived;

            // Generate description
            $description = 'Permission for route: ' . $route;

            return response()->json([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate permission data.'], 500);
        }
    }
}
