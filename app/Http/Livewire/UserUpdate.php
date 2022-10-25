<?php

namespace App\Http\Livewire;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UserUpdate extends Component
{
    /** @var User */
    public $user;

    public $name;

    public $username;

    public $email;

    public $phone;

    public $password;

    public $role;

    public $enabled;

    public $verified;

    public $business;

    public $balance;

    public function mount(User $user)
    {
        if ($user->can('manage') && !Gate::check('administer')) {
            abort(403);
        }

        $this->user = $user;
        $this->fill($user);
        $this->balance = $user->balance;
    }

    public function render()
    {
        return view('livewire.user-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'regex:/^\w[\w.]+\w$/', 'min:3', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\+\d+$/', 'max:15'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['nullable', 'string', Rule::in(array_keys(config('fixtures.user_roles')))],
            'enabled' => ['nullable', 'boolean'],
            'verified' => ['nullable', 'boolean'],
            'business' => ['nullable', 'boolean'],
            'balance' => ['required', 'integer', 'min:0'],
        ]);
        $exists = User::query()
            ->whereKeyNot($this->user->id)
            ->where('username', $data['username'])
            ->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'username' => __('validation.unique', ['attribute' => 'username'])
            ]);
        }

        if ($data['email'] ?? null) {
            $exists = User::query()
                ->whereKeyNot($this->user->id)
                ->where('email', $data['email'])
                ->exists();
            if ($exists) {
                throw ValidationException::withMessages([
                    'email' => __('validation.unique', ['attribute' => 'email'])
                ]);
            }
        }

        if ($data['phone'] ?? null) {
            $exists = User::query()
                ->whereKeyNot($this->user->id)
                ->where('phone', $data['phone'])
                ->exists();
            if ($exists) {
                throw ValidationException::withMessages([
                    'phone' => __('validation.unique', ['attribute' => 'phone'])
                ]);
            }
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if (!Gate::check('administer')) {
            unset($data['role']);
        }

        $this->user->fill($data);
        $this->user->verified = !empty($data['verified']);
        $this->user->save();
        if ($data['balance'] != $this->user->balance) {
            if ($data['balance'] > $this->user->balance) {
                $this->user->deposit($difference = $data['balance'] - $this->user->balance);
                activity('update')
                    ->on($this->user)
                    ->withProperties(['attributes' => ['amount' => $difference]])
                    ->log('User balance was added.');
            } else {
                $this->user->withdraw($difference = $this->user->balance - $data['balance']);
                activity('update')
                    ->on($this->user)
                    ->withProperties(['attributes' => ['amount' => -$difference]])
                    ->log('User balance was deducted.');
            }
        }

        flash()->info(__('User :name has been updated.', ['name' => $this->user->name]));
        $this->redirect(route('users.show', $this->user));
    }
}
