<?php

namespace App\View\Components;

use Closure;
use App\SmStaff;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FrontendTeacherList extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        $data['teachers'] = SmStaff::where('is_saas', 0)
                ->where('school_id', app('school')->id)
                ->where('role_id', 4)
                ->with(array('roles' => function ($query) {
                    $query->select('id', 'name');
                }))
                ->with(array('departments' => function ($query) {
                    $query->select('id', 'name');
                }))
                ->with(array('designations' => function ($query) {
                    $query->select('id', 'title');
                }))
                ->get();

        return view('components.' . activeTheme() . '.frontend-teacher-list', $data);
    }
}
