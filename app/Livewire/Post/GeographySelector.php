<?php

namespace App\Livewire\Post;

use App\Models\Makerlebal;
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Mregion;
use App\Models\Muser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GeographySelector extends Component
{
    public $geographyOptions = [];

    public $filteredOptions = [];

    public $selectedGeography = null;

    public $changeTitle = 'Select';

    public $c2rselectId;
    public $user;
    public $allowGeo;
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
        case 3: // Region
            $query = Mregion::where('isActive', 1);

           $query=$this->getGeographyOptions($query, 'regionId','r');

            $this->filteredOptions = $query->get(['regionId as id', 'regionnameInEnglish as name']);
            $this->changeTitle = 'Region';
            break;

        case 2: // City
            $query = Mcity::where('isActive', 1);

            $query=$this->getGeographyOptions($query, 'cityId','c');

            $this->filteredOptions = $query->get(['cityId as id', 'citynameInEnglish as name']);
            $this->changeTitle = 'City';
            break;

        case 1: // Country
            $query = Mcountry::where('isActive', 1);

            $query=$this->getGeographyOptions($query, 'countryId','con');

            $this->filteredOptions = $query->get(['countryId as id', 'countryNameInEnglish as name']);
            $this->changeTitle = 'Country';
            break;

        default:
            $this->changeTitle = 'Select';
            break;
    }
}

private function getGeographyOptions($query, $field, $con)
{
    // If Admin, no filtering needed
    if (Auth::guard('admin')->check()) {
        return $query;
    }

    // Normal User - fetch user's allowed geography
    $userId = auth()->user()->userId ?? Auth::guard('admin')->user()->userId;

    $user = Muser::find($userId);

    if (!$user) {
        return $query; // no user found, skip
    }

    $this->allowGeo = collect($user->geography ?? [])->reduce(function ($carry, $item) {
        if (preg_match('/[a-zA-Z]+/', $item, $key) && preg_match_all('/\d+/', $item, $numbers)) {
            foreach ($numbers[0] as $num) {
                $carry[$key[0]][] = (int) $num;
            }
        }
        return $carry;
    }, []);

    // Apply whereIn if allowed geographies exist
    if (!empty($this->allowGeo[$con])) {
        $query->whereIn($field, $this->allowGeo[$con]);
    }else{
        $query->whereIn($field, [3]);
    }

    return $query;
}


    public function render()
    {
        return view('livewire.post.geography-selector');
    }
}
