<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
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

        return redirect()->route('users.index')
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

        return redirect()->route('users.index')
                         ->with('success', __('admin.user_updated'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $this->userService->delete($user);

        return redirect()->route('users.index')
                         ->with('success', __('admin.user_deleted'));
    }
}
