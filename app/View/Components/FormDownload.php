<?php

namespace App\View\Components;

use Closure;
use App\Models\SmFormDownload;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FormDownload extends Component
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
        $data['formDownloads'] = SmFormDownload::where('school_id', app('school')->id)->where('show_public', 1)->get();
        return view('components.' . activeTheme() . '.form-download', $data);
    }
}
