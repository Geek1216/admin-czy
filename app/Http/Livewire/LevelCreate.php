<?php

namespace App\Http\Livewire;

use App\Level;
use Livewire\Component;

class LevelCreate extends Component
{
    public $name;

    public $color;

    public $order = 99;

    public $followers = -1;

    public $uploads = -1;

    public $views = -1;

    public $likes = -1;

    public $reward = 0;

    public function render()
    {
        return view('livewire.level-create');
    }

    public function create()
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
        /** @var Level $level */
        $level = Level::create($data);
        flash()->success(__('Level :name has been successfully added.', ['name' => $level->name]));
        $this->redirect(route('levels.show', $level));
    }
}
