<?php

namespace App\Livewire\Post;

use Livewire\Component;

class AdSelector extends Component
{

    public $selectedAds = [];

    public $chittiId;



    public function mount($chittiId)
    {
        $this->chittiId = $chittiId;
    }


    public function render()
    {
        return view('livewire.post.ad-selector');
    }
}
