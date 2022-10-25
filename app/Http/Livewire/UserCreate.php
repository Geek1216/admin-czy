<?php

namespace App\Http\Livewire;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserCreate extends Component
{
    public $name;

    public $username;

    public $email;

    public $phone;

    public $password;

    public $role;

    public $enabled = true;

    public $verified;

    public $business;

    public function render()
    {
        return view('livewire.user-create');
    }

    public function create()
    {
        $roles = array_keys(config('fixtures.user_roles'));
        if (!Gate::check('administer')) {
            $roles = array_filter($roles, function ($value) {
                return $value !== 'admin';
            });
        }

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'regex:/^\w[\w.]+\w$/', 'min:3', 'max:30', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'regex:/^\+\d+$/', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['nullable', 'string', Rule::in($roles)],
            'enabled' => ['nullable', 'boolean'],
            'verified' => ['nullable', 'boolean'],
            'business' => ['nullable', 'boolean'],
        ]);
        $data['password'] = Hash::make($data['password']);
        if (!Gate::check('administer')) {
            unset($data['role']);
        }

        $user = User::make($data);
        $user->verified = !empty($data['verified']);
        $user->business = !empty($data['business']);
        $user->save();
        flash()->success(__('User :name has been successfully added.', ['name' => $user->name]));
        $this->redirect(route('users.show', $user));
    }
}
