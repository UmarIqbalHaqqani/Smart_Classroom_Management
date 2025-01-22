<?php

namespace App\View\Components;

use Closure;
use App\SmNews;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class News extends Component
{
    public $count ;
    public $colum;
    public $readmore;
    public $sorting;

    public function __construct($count = 4, $colum=4, $readmore="Read More",$sorting = 'asc')
    {
        $this->count = $count;
        $this->colum = $colum;
        $this->readmore = $readmore;
        $this->sorting = $sorting;
    }

    
    public function render(): View|Closure|string
    {
        $news = SmNews::query();
        $news->where('school_id', app('school')->id)->where('status', 1)->where('mark_as_archive',0);

        if($this->sorting =='asc'){
            $news->orderBy('id','asc');
        }
        elseif($this->sorting =='desc'){
            $news->orderBy('id','desc');
        }
        else{
            $news->inRandomOrder();
        }

        $news = $news->take($this->count)->get();
        return view('components.'.activeTheme().'.news',compact('news'));
    }
}
