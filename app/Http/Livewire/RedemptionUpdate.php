<?php

namespace App\Http\Livewire;

use App\Jobs\SendNotification;
use App\Redemption;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RedemptionUpdate extends Component
{
    public $redemption;

    public $status;

    public $notes;

    public function mount(Redemption $redemption)
    {
        $this->redemption = $redemption;
        $this->fill($redemption);
    }

    public function render()
    {
        return view('livewire.redemption-update');
    }

    public function update()
    {
        $data = $this->validate([
            'status' => ['required', 'string', Rule::in(array_keys(config('fixtures.redemption_statuses')))],
            'notes' => ['nullable', 'string', 'max:1024'],
        ]);
        $this->redemption->fill($data);
        $notify = $this->redemption->isDirty('status') && $this->redemption->status !== 'pending';
        $this->redemption->save();
        if ($notify && $this->redemption->status === 'approved') {
            dispatch(new SendNotification(
                __('notifications.redemption_approved.title'),
                __('notifications.redemption_approved.body'),
                null,
                $this->redemption->user
            ));
        } else if ($notify) {
            dispatch(new SendNotification(
                __('notifications.redemption_rejected.title'),
                __('notifications.redemption_rejected.body'),
                null,
                $this->redemption->user
            ));
        }
        flash()->info(__('Redemption #:id has been updated.', ['id' => $this->redemption->id]));
        $this->redirect(route('redemptions.show', $this->redemption));
    }
}
