<?php

namespace App\View\Components;

use App\SmEvent;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventGallery extends Component
{
    public $count;
    public $column;
    public $sorting;
    public $button;
    public $dateshow;
    public $enevtlocation;

    public function __construct($count = 4, $column = 4, $sorting = 'asc', $button = 'View Details', $dateshow = 1, $enevtlocation = 1)
    {
        $this->count = $count;
        $this->column = $column;
        $this->sorting = $sorting;
        $this->button = $button;
        $this->dateshow = $dateshow;
        $this->enevtlocation = $enevtlocation;
    }

    public function render(): View|Closure|string
    {
        $events = SmEvent::query();
        $events = $events->where('school_id', app('school')->id);

        if ($this->sorting == 'asc') {
            $events->orderBy('id', 'asc');
        } elseif ($this->sorting == 'desc') {
            $events->orderBy('id', 'desc');
        } else {
            $events->inRandomOrder();
        }

        $events = $events->take($this->count)->get();

        return view('components.' . activeTheme() . '.event-gallery', compact('events'));
    }
}
