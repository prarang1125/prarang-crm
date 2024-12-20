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

    public $changeTitle = 'Select';

    public $c2rselectId;

    public function mount($geography = '-', $c2rselect = '-')
    {

        $this->geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        if ($geography) {
            $this->selectedGeography = $geography;
            $this->c2rselectId = $c2rselect;
            $this->changeGeography();
        }
    }

    public function changeGeography()
    {
        $this->filteredOptions = [];

        switch ($this->selectedGeography) {
            case 5: // Region
                $this->filteredOptions = Mregion::where('isActive', 1)
                    ->get(['regionId as id', 'regionnameInEnglish as name']);
                $this->changeTitle = 'Region';
                break;
            case 6: // City
                $this->filteredOptions = Mcity::where('isActive', 1)
                    ->get(['cityId as id', 'citynameInEnglish as name']);
                $this->changeTitle = 'City';
                break;
            case 7: // Country
                $this->filteredOptions = Mcountry::where('isActive', 1)
                    ->get(['countryId as id', 'countryNameInEnglish as name']);
                $this->changeTitle = 'Country';
                break;
            default:
                $this->changeTitle = 'Select';
                break;
        }
    }

    public function render()
    {
        return view('livewire.post.geography-selector');
    }
}
