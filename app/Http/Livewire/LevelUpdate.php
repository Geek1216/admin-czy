<?php

namespace App\Http\Livewire;

use App\Level;
use Livewire\Component;

class LevelUpdate extends Component
{
    public $level;

    public $name;

    public $color;

    public $order = 99;

    public $followers = -1;

    public $uploads = -1;

    public $views = -1;

    public $likes = -1;

    public $reward = 0;

    public function mount(Level $level)
    {
        $this->level = $level;
        $this->fill($level);
    }

    public function render()
    {
        return view('livewire.level-update');
    }

    public function update()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'size:6'],
            'order' => ['required', 'integer', 'max:65535'],
            'followers' => ['required', 'integer', 'min:-1'],
            'uploads' => ['required', 'integer', 'min:-1'],
            'views' => ['required', 'integer', 'min:-1'],
            'likes' => ['required', 'integer', 'min:-1'],
            'reward' => ['required', 'integer', 'min:0'],
        ]);
        $this->level->fill($data);
        $this->level->save();
        flash()->info(__('Level :name has been updated.', ['name' => $this->level->name]));
        $this->redirect(route('levels.show', $this->level));
    }
}
