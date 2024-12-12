<?php

namespace App\View\Components\Post\Maker;

use App\Models\Chitti;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChangeTitle extends Component
{
    /**
     * Create a new component instance.
     */
    public $chittiId;
    public function __construct($chittiId)
    {
        $this->chittiId=$chittiId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.post.maker.change-title');
    }
}
