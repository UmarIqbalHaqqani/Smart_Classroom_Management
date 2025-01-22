<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\FrontAcademicCalendar;

class FrontendAcademicCalendar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $frontAcademicCalendars = FrontAcademicCalendar::where('school_id', app('school')->id)->get();
        return view('components.' . activeTheme() . '.frontend-academic-calendar', compact('frontAcademicCalendars'));
    }
}
