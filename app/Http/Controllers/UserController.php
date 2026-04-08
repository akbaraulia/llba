<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();

        $users = User::query()
            ->when($search, function ($query, $searchValue) {
                $query->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhere('phone', 'like', "%{$searchValue}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $allowedRoles = $request->user()?->isAdmin()
            ? ['admin', 'petugas', 'member']
            : ['member'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'unique:users,phone'],
            'role' => ['required', Rule::in($allowedRoles)],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if (! $request->user()?->isAdmin()) {
            $data['role'] = 'member';
        }

        $user = User::create($data);

        if ($user->isMember()) {
            Member::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'points' => 0,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $allowedRoles = $request->user()?->isAdmin()
            ? ['admin', 'petugas', 'member']
            : ['member'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'role' => ['required', Rule::in($allowedRoles)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! filled($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        if ($user->isMember()) {
            Member::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'points' => 0,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Akun yang sedang dipakai tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
