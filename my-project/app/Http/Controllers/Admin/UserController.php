<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use App\Enums\UserRole;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $page = request()->get('page', 1);
        $users = Cache::remember('users_list_page_' . $page, 300, function () {
            return User::paginate(10);
        });

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        $this->userService->store($request->validated());

        return redirect()->route('admin.users.index')
                         ->with('success', __('admin.user_created'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $this->userService->update($user, $request->validated());

        return redirect()->route('admin.users.index')
                         ->with('success', __('admin.user_updated'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $this->userService->delete($user);

        return redirect()->route('admin.users.index')
                         ->with('success', __('admin.user_deleted'));
    }

    public function updateRole(UpdateUserRoleRequest $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Safety: Prevent removing last admin
        if ($user->isAdmin() && $request->role !== UserRole::Admin->value) {
            $adminCount = User::where('role', UserRole::Admin->value)->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'message' => 'Impossible de retirer le rôle du dernier administrateur.'
                ], 422);
            }
        }

        $user->update(['role' => $request->role]);

        return response()->json([
            'message' => 'Rôle mis à jour avec succès.',
            'user' => $user
        ]);
    }
}
