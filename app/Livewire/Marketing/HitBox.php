<?php

namespace App\Livewire\Marketing;

use Livewire\Component;

class HitBox extends Component
{
    public function render()
    {
        return view('livewire.marketing.hit-box')->layout('layouts.admin.admin');
    }
}
