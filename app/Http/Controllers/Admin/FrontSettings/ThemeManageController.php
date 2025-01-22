<?php
namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmGeneralSettings;
use App\Traits\UploadTheme;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Cache;
use Modules\RolePermission\Entities\Permission;

class ThemeManageController extends Controller
{
    use UploadTheme;



    public function index()
    {
       
        return view('backEnd.themeManager.allThemes');
    }


    public function create()
    {
        try {
            return view('appearance::theme.components.create');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function active(Request $request)
    {
        if (demoCheck() || env('DEMO_MODE')) {
            return redirect()->back();
        }
        try {
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function install(Request $request)
    {
        try {
            if(config('app.app_sync')){
                Toastr::error('Restricted in demo mode');
                return back();
            }
            $edulia_routes = ['home-slider','expert-teacher','photo-gallery','video-gallery','front-result','front-class-routine','front-exam-routine','front-academic-calendar','news-comment-list','pagebuilder'];
            $default_routes = ['admin-home-page','conpactPage','about-page','news-heading-update','course-heading-update','custom-links','social-media','course-details-heading','class-exam-routine-page','exam-result-page'];
            $generalSettData = SmGeneralSettings::where('school_id', auth()->user()->school_id)->first();
            $edulia_status = 0;
            $default_status = 0;            
            if($request->theme == 'edulia'){
                $edulia_status = 1;
                $generalSettData->active_theme  = $request->theme;
            }else{
                $default_status = 1;
                $generalSettData->active_theme  = null;
            }
            $results = $generalSettData->save();
            
            session()->forget('generalSetting');
            session()->put('generalSetting', $generalSettData);

            Permission::where('school_id', auth()->user()->school_id)
            ->whereIn('route',$edulia_routes)->update(['status'=>$edulia_status,'menu_status'=>$edulia_status]);

            Permission::where('school_id', auth()->user()->school_id)
            ->whereIn('route',$default_routes)->update(['status'=>$default_status,'menu_status'=>$default_status]);

           
            Cache::forget('sidebars' . auth()->user()->id);
            Toastr::success("Success", 'Theme Changes Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        try {
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function destroy(Request $request)
    {
        if (demoCheck() || env('DEMO_MODE')) {
            return redirect()->back();
        }
        try {
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function demo()
    {
        try {
            return view('appearance::theme.demo');
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function demoSubmit(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $request->validate([
            'demo' => ['required', 'mimes:zip'],
        ]);
        try {


        } catch (\Exception $e) {

        }
    }
}
