<?php

namespace App\View\Components;

use Closure;
use App\Models\SmDonor;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Donor extends Component
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
        $data['donors'] = SmDonor::where('school_id', app('school')->id)->where('show_public', 1)->get();
        return view('components.' . activeTheme() . '.donor', $data);
    }
}
