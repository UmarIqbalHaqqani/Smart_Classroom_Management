<?php

namespace App\View\Components;

use App\SmHeaderMenuManager;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderContentMenu extends Component
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
        $menus = SmHeaderMenuManager::where('school_id', app('school')->id)->where('theme', 'edulia')->whereNull('parent_id')->orderBy('position')->get();

        return view('components.'.activeTheme().'.header-content-menu',compact('menus'));
    }
}
