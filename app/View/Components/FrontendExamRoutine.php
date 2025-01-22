<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use App\Models\FrontExamRoutine;
use Illuminate\Contracts\View\View;

class FrontendExamRoutine extends Component
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
        $frontExamRoutines = FrontExamRoutine::where('school_id', app('school')->id)->get();
        return view('components.' . activeTheme() . '.frontend-exam-routine', compact('frontExamRoutines'));
    }
}
