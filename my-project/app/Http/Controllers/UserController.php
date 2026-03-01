<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:admin,student,entreprise'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'company_name' => $request->company_name ?? null,
            'company_address' => $request->company_address ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,student,entreprise'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'company_name' => $request->company_name ?? null,
            'company_address' => $request->company_address ?? null,
        ]);

        if($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}