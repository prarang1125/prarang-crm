<?php

namespace App\Livewire\Post;

use App\Models\Ad;
use App\Models\AdChittiMap;
use Livewire\Component;

class AdSelector extends Component
{
    public $chittiId;

    public bool $showModal = false;

    // ad_code (1,2,3,4) => ad_id
    public array $selectedAds = [
        1 => null,
        2 => null,
        3 => null,
        4 => null,
    ];

    public $ads = [];

    protected $rules = [
        'selectedAds.1' => 'nullable|integer|exists:ads,id',
        'selectedAds.2' => 'nullable|integer|exists:ads,id',
        'selectedAds.3' => 'nullable|integer|exists:ads,id',
        'selectedAds.4' => 'nullable|integer|exists:ads,id',
    ];

    public function mount($chittiId)
    {
        $this->chittiId = $chittiId;

        $this->loadAds();
        $this->loadExistingSelection();
    }

    public function loadAds()
    {
        $this->ads = Ad::where('status', 1)
            ->orderBy('ad_title')
            ->get(['id', 'ad_title'])
            ->toArray();
    }

    public function loadExistingSelection()
    {
        // reset first, warna purani values reh jayengi
        $this->selectedAds = [1 => null, 2 => null, 3 => null, 4 => null];

        $existing = AdChittiMap::where('chitti_id', $this->chittiId)
            ->where('status', 1)
            ->get();

        foreach ($existing as $row) {
            if (array_key_exists($row->ad_code, $this->selectedAds)) {
                $this->selectedAds[$row->ad_code] = $row->ad_id;
            }
        }
    }

    public function openModal()
    {
        $this->loadExistingSelection();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $hasAtLeastOne = collect($this->selectedAds)
            ->filter(fn ($v) => !empty($v))
            ->isNotEmpty();

        if (! $hasAtLeastOne) {
            $this->addError('selectedAds', 'Kam se kam 1 ad select karna zaroori hai.');
            return;
        }

        foreach ($this->selectedAds as $adCode => $adId) {
            if (empty($adId)) {
                // us position ka ad hata diya gaya hai -> map delete/inactive
                AdChittiMap::where('chitti_id', $this->chittiId)
                    ->where('ad_code', $adCode)
                    ->delete();
                continue;
            }

            AdChittiMap::updateOrCreate(
                [
                    'chitti_id' => $this->chittiId,
                    'ad_code'   => $adCode,
                ],
                [
                    'ad_id'  => $adId,
                    'status' => 1,
                ]
            );
        }

        $this->dispatch('ads-updated', chittiId: $this->chittiId);

        session()->flash('ad_selector_success', 'Ads successfully update ho gaye.');

        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.post.ad-selector');
    }
}
