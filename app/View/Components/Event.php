<?php

namespace App\View\Components;

use Closure;
use App\SmEvent;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Event extends Component
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
        $events = SmEvent::where('school_id', app('school')->id)->take($this->count)->get(['id', 'from_date', 'to_date', 'event_title', 'event_location']);
        return view('components.'.activeTheme().'.event', compact('events'));
    }
}
