<?php

namespace App\View\Components;

use Closure;
use App\Models\SmPhotoGallery;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class PhotoGallery extends Component
{
    public $count;
    public $column;
    public $sorting;
    /**
     * Create a new component instance.
     */
    public function __construct($count, $column)
    {
        $this->count  = $count;
        $this->column = $column;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $photoGalleries = SmPhotoGallery::where('parent_id', '=', null)
                        ->where('school_id', app('school')->id)
                        ->orderBy('position','asc')
                        ->get();    

        $photoGalleryCount = SmPhotoGallery::where('school_id', app('school')->id)->count();
                   
        return view('components.' . activeTheme() . '.photo-gallery', [
            'photoGalleries' => $photoGalleries,
            'column' => $this->column,
            'count' => $this->count,
            'photoGalleryCount' => $photoGalleryCount
        ]);
    }
}
