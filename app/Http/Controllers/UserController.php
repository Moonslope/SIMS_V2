<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    // Search first name
                    $q->where('first_name', 'LIKE', "%{$search}%")
                        // Search middle name
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        // Search last name
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        // Search email
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        // Search role
                        ->orWhere('role', 'LIKE', "%{$search}%")
                        // Search full name (first + last)
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?  ", ["%{$search}%"])
                        // Search full name (first + middle + last)
                        ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ? ", ["%{$search}%"]);
                });
            })
            ->orderBy('last_name', 'asc')
            ->paginate(10);

        return view('system.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('system.users.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, UserRequest $userRequest)
    {
        $validated = $userRequest->validated();
        $user = User::create($validated);

        // Log activity
        ActivityLogService::created($user, "Created user: '{$user->first_name} {$user->last_name}'");

        return redirect()->route('users.index')->with('success', 'New user has been added successfully.');
    }

    /**
     * Display the authenticated user's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('dashboardProfile', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */

    public function updateProfile(UserRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $oldName = "{$user->first_name} {$user->last_name}";

        // Update basic info
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        // Log activity
        $newName = "{$user->first_name} {$user->last_name}";
        if ($oldName !== $newName) {
            ActivityLogService::updated($user, "Updated profile from '{$oldName}' to '{$newName}'");
        } else {
            ActivityLogService::updated($user, "Updated profile: '{$newName}'");
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('system.users.edit_new', compact('user'));
    }

    /**
     * Update the specified resource in storage. 
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $oldName = "{$user->first_name} {$user->last_name}";

        $validated = $request->validated();

        $user->update($validated);

        // Log activity
        $newName = "{$user->first_name} {$user->last_name}";
        if ($oldName !== $newName) {
            ActivityLogService::updated($user, "Updated user from '{$oldName}' to '{$newName}'");
        } else {
            ActivityLogService::updated($user, "Updated user: '{$newName}'");
        }

        return redirect()->route('users.index')->with('success', 'User has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage. 
     */
    public function destroy(string $id)
    {
        //
    }
}
