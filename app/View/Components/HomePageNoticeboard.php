<?php

namespace App\View\Components;

use App\SmNoticeBoard;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HomePageNoticeboard extends Component
{
    public $count;
    public $sorting;

    public function __construct($count = 10, $sorting = 'asc')
    {
        $this->count = $count;
        $this->sorting = $sorting;
    }

    public function render(): View|Closure|string
    {
        $noticeBoards = SmNoticeBoard::query();
        $noticeBoards = $noticeBoards->where('publish_on', '<=', date('Y-m-d'))->where('is_published',1)->where('school_id', app('school')->id);
        if($this->sorting =='asc'){
            $noticeBoards->orderBy('id','asc');
        }elseif($this->sorting =='desc'){
            $noticeBoards->orderBy('id','desc');
        }else{
            $noticeBoards->inRandomOrder();
        }
        $noticeBoards = $noticeBoards->take($this->count)->get();
        return view('components.'.activeTheme().'.home-page-noticeboard',compact('noticeBoards'));
    }
}
