<?php

namespace App\View\Components;

use App\Models\SmVideoGallery;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VideoGallery extends Component
{
    public $count;
    public $column;
    public $sorting;
    /**
     * Create a new component instance.
     */
    public function __construct($count = 3, $column = 4)
    {
        $this->count = $count;
        $this->column = $column;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $videoGalleries = SmVideoGallery::where('school_id', app('school')->id)
                        ->take($this->count)
                        ->orderBy('position')
                        ->get();
        return view('components.' . activeTheme() . '.video-gallery', compact('videoGalleries'));
    }
}
