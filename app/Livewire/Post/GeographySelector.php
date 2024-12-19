<?php

namespace App\Livewire\Post;

use App\Models\Makerlebal;
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Mregion;
use Livewire\Component;

class GeographySelector extends Component
{
    public $geographyOptions = [];

    public $filteredOptions = [];

    public $selectedGeography = null;

    public function mount()
    {
        // Preload the geography options (IDs 5, 6, 7)
        $this->geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
    }

    public function updateFilteredOptions($value)
    {
        // Reset filtered options
        $this->filteredOptions = [];

        // Load data based on selected geography
        switch ($value) {
            case 5: // Region
                $this->filteredOptions = Mregion::where('isActive', 1)->get();
                break;
            case 6: // City
                $this->filteredOptions = Mcity::where('isActive', 1)->get();
                break;
            case 7: // Country
                $this->filteredOptions = Mcountry::where('isActive', 1)->get();
                break;
        }

        // Set selectedGeography to the chosen value
        $this->selectedGeography = $value;
    }

    public function render()
    {
        return view('livewire.post.geography-selector');
    }
}
