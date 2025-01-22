<?php

namespace App\View\Components;

use Closure;
use App\Models\SpeechSlider;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SmSpeechSlider extends Component
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
        $speechSliders = SpeechSlider::where('school_id', app('school')->id)->take($this->count)->get();
        return view('components.'.activeTheme().'.sm-speech-slider', compact('speechSliders'));
    }
}
