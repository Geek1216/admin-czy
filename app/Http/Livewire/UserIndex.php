<?php

namespace App\Http\Livewire;

use App\Jobs\SendNotification as SendNotificationJob;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $boost = false;
    /** @var User|null */
    public $boostable = null;
    public $boostCount = 100;
    public $boostType = null;

    public $business;

    public $enabled = 'true';

    public $filtering = false;

    public $length = '10';

    public $order = ['created_at' => 'desc'];

    public $role;

    public $search;

    public $verified;

    public function filter()
    {
        $this->filtering = !$this->filtering;
    }

    public function render()
    {
        $query = User::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('name', 'like', "%$this->search%")
                    ->orWhere('email', 'like', "%$this->search%")
                    ->orWhere('phone', 'like', "%$this->search%")
                    ->orWhere('username', 'like', "%$this->search%");
            });
        }

        if ($this->role) {
            $query->where('role', $this->role);
        }

        if ($this->verified) {
            $query->where('verified', $this->verified === 'true');
        }

        if ($this->business) {
            $query->where('business', $this->business === 'true');
        }

        if ($this->enabled) {
            $query->where('enabled', $this->enabled === 'true');
        }

        foreach ($this->order as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        $users = $query->paginate($this->length);
        return view('livewire.user-index', compact('users'));
    }

    /**
     * @param string $column
     * @param string|false $direction
     */
    public function sort(string $column, $direction)
    {
        if ($direction) {
            $this->order[$column] = $direction;
        } else {
            unset($this->order[$column]);
        }

        $this->resetPage();
    }

    public function suggest(int $id, bool $notification)
    {
        /** @var User $user */
        $user = User::query()->findOrFail($id);
        if ($notification) {
            dispatch(new SendNotificationJob(
                __('notifications.follow_suggestion.title', ['user' => $user->username]),
                __('notifications.follow_suggestion.body'),
                ['user' => $user->id]
            ));
        } else {
            try {
                $user->suggestion()->create(['order' => 99]);
            } catch (QueryException $e) {
            }
        }
    }

    public function updatingBusiness()
    {
        $this->resetPage();
    }

    public function updatingEnabled()
    {
        $this->resetPage();
    }

    public function updatingLength()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingVerified()
    {
        $this->resetPage();
    }

    public function hideBoostDialog()
    {
        $this->boost = false;
        $this->boostable = $this->boostType = null;
        $this->boostCount = 100;
    }

    public function showBoostDialog(int $id, string $type)
    {
        $this->boost = true;
        $this->boostable = User::query()->findOrFail($id);
        $this->boostCount = 100;
        $this->boostType = $type;
    }

    public function submitBoost()
    {
        $this->validate([
            'boostCount' => ['required', 'numeric', 'min:100', 'max:99999'],
        ]);
        User::query()
            ->whereKeyNot($this->boostable->getKey())
            ->take($this->boostCount)
            ->inRandomOrder()
            ->each(function (User $user) {
                switch ($this->boostType) {
                    case 'followers':
                        if (!$this->boostable->isFollowedBy($user)) {
                            $user->follow($this->boostable);
                        }
                        Cache::forget("user_{$this->boostable->id}_followers");
                        break;
                    default:
                        break;
                }
            });
        flash()->success(__('Successfully boosted :type for selected user.', ['type' => $this->boostType]));
        $this->redirectRoute('users.index');
    }
}
