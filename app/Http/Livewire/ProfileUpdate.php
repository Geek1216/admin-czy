<?php

namespace App\Http\Livewire;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ProfileUpdate extends Component
{
    public $name;

    public $email;

    public $password;

    public function mount()
    {
        $this->fill(Auth::user());
    }

    public function render()
    {
        return view('livewire.profile-update');
    }

    public function update()
    {
        /** @var User $user */
        $user = Auth::user();
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data);
        $user->save();
        flash()->info(__('Your profile information has been updated.'));
        $this->redirect(route('profile'));
    }
}
