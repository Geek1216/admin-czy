<?php

namespace App\Http\Livewire;

use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class UserDestroy extends Component
{
    use AuthorizesRequests;

    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user-destroy');
    }

    public function destroy()
    {
        $this->authorize('administer');
        if (!Gate::check('administer') && $this->user->can('administer')) {
            flash()->warning(__('You cannot deleted an administrator.'));
        } else if ($this->user->id !== Auth::id()) {
            $this->user->delete();
            flash()->info(__('User :name has been deleted.', ['name' => $this->user->name]));
            $this->redirect(route('users.index'));
        } else {
            flash()->warning('You cannot delete yourself from system.');
        }

        $this->redirect(route('users.show', $this->user));
    }
}
