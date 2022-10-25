<?php

namespace App\Http\Livewire;

use App\Jobs\SendNotification;
use App\Verification;
use Illuminate\Validation\Rule;
use Livewire\Component;

class VerificationUpdate extends Component
{
    public $verification;

    public $status;

    public function mount(Verification $verification)
    {
        $this->verification = $verification;
        $this->fill($verification);
    }

    public function render()
    {
        return view('livewire.verification-update');
    }

    public function update()
    {
        $data = $this->validate([
            'status' => ['required', 'string', Rule::in(array_keys(config('fixtures.verification_statuses')))],
        ]);
        $this->verification->fill($data);
        $verify = $this->verification->isDirty('status') && $this->verification->status === 'accepted';
        $this->verification->save();
        if ($verify) {
            $this->verification->user->verified = true;
            if ($this->verification->business) {
                $this->verification->user->business = true;
            }

            $this->verification->user->save();
            dispatch(new SendNotification(
                __('notifications.verification_approved.title'),
                __('notifications.verification_approved.body'),
                ['user' => $this->verification->user->id],
                $this->verification->user,
                null,
                false
            ));
        }

        flash()->info(__('Verification #:id has been updated.', ['id' => $this->verification->id]));
        $this->redirect(route('verifications.show', $this->verification));
    }
}
