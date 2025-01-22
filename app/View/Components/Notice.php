<?php

namespace App\View\Components;

use Closure;
use App\SmNoticeBoard;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Notice extends Component
{
    public $count;
    public $btn;
    /**
     * Create a new component instance.
     */
    public function __construct($count = 3, $btn = "__('View Detail')")
    {
        $this->count = $count;
        $this->btn = $btn;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $notices = SmNoticeBoard::where('publish_on', '<=', date('Y-m-d'))->where('is_published',1)->where('school_id', app('school')->id)->take($this->count)->get(['id','notice_title', 'notice_date', 'is_published']);
        return view('components.' . activeTheme() . '.notice', compact('notices'));
    }
}
