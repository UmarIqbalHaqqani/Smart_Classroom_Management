<?php

namespace App\View\Components;

use Closure;
use App\Models\HomeSlider;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class HomePageSlider extends Component
{
    public $count;
    /**
     * Create a new component instance.
     */
    public function __construct($count = 3)
    {
        $this->count = $count;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $homeSliders = HomeSlider::where('school_id', app('school')->id)->take($this->count)->get();
        return view('components.'.activeTheme().'.home-page-slider', compact('homeSliders'));
    }
}
