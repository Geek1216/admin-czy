<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class DotenvUpdate extends Component
{
    public $env;

    public function mount()
    {
        $this->env = file_get_contents(base_path('.env'));
    }

    public function render()
    {
        return view('livewire.dotenv-update');
    }

    public function update()
    {
        $data = $this->validate([
            'env' => ['required', 'string'],
        ]);
        file_put_contents(base_path('.env'), $data['env']);
        flash()->info(__('.env file has been successfully rewritten.'));
        $this->redirect(route('dotenv.update'));
    }

    public function cacheConfig()
    {
        Artisan::call('config:cache', []);
        flash()->info(__('Configuration has been refresh with latest .env contents.'));
        $this->redirect(route('dotenv.update'));
    }

    public function purgeCache()
    {
        tagged_cache(['counts'])->flush();
        flash()->info(__('Cached counts (followers, likes, views etc.) have been purged.'));
        $this->redirectRoute('dotenv.update');
    }

    public function restartQueue()
    {
        Artisan::call('queue:restart', []);
        flash()->info(__('Restarted has been triggered for configured queue workers.'));
        $this->redirect(route('dotenv.update'));
    }
}
