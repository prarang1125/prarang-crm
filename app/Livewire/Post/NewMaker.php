<?php

namespace App\Livewire\Post;

use App\Models\Intent;
use Livewire\Component;

class NewMaker extends Component
{
    public $intent,$summary,$intent_type;

    public function mount($id = null)
    {

        if ($id) {
            $intentDb = Intent::where('chittiId',$id)->first();

            if ($intentDb) {
                $this->intent = $intentDb->intent;
                $this->summary = $intentDb->summary;
                $this->intent_type = $intentDb->intent_type;
            }
        }

    }

    public function render()
    {
        return view('livewire.post.new-maker');
    }
}
