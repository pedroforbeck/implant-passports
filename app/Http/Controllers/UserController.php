<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $users = User::query()
            ->when(
                Role::tryFrom((string) $request->query('role')),
                fn ($query, Role $role) => $query->withRole($role),
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => Role::cases(),
        ]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::cases(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário removido.');
    }
}
