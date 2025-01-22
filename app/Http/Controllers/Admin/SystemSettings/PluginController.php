<?php

namespace App\Http\Controllers\Admin\SystemSettings;

use App\Models\Plugin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Entities\InfixRole;

class PluginController extends Controller
{
    public function tawkSetting(){
        
        try{
            $data = [];
            $data['roles'] = InfixRole::where('is_saas',0)->where('id','!=',1)->get();
            $data['pt'] = __('system_settings.Tawk To Chat Setting');
            $data['setting'] = Plugin::where('name','tawk')->where('school_id',auth()->user()->school_id)->first();
            return view('backEnd.systemSettings.plugin_setting',$data);
            
        }
        catch(\Exception $e){

        }

    }

    public function tawkSettingUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'is_enable'         => 'required|numeric|in:0,1',
            'availability'      => 'required',
            'show_admin_panel'  => 'required|numeric|in:0,1',
            'show_website'      => 'required|numeric|in:0,1',
            'applicable_for'    => 'required',
            'position'          => 'required|string',
            'showing_page'      => 'required',
            'short_code'        => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
        
            Toastr::error($errorMessage, 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try{
            $setting = Plugin::where('name','tawk')->where('school_id',auth()->user()->school_id)->first();
            if($setting){
                $setting->is_enable = $request->is_enable; 
                $setting->availability = $request->availability; 
                $setting->show_admin_panel = $request->show_admin_panel ; 
                $setting->show_website = $request->show_website ; 
                $setting->applicable_for = $request->applicable_for ; 
                $setting->position = $request->position ; 
                $setting->showing_page = $request->showing_page ;
                $setting->short_code = $request->short_code ; ; 
                $setting->school_id = auth()->user()->school_id; 
                $setting->save();
                Toastr::success('Tawk to settings Successfully Updated', 'Success'); 
                return redirect()->back();
            }
        }
        catch(\Exception $e){
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }


    public function messengerSetting(){
        try{
            $data = [];
            $data['roles'] = InfixRole::where('is_saas',0)->where('id','!=',1)->get();
            $data['pt'] = __('system_settings.Messenger Chat Setting');
            $data['setting'] = Plugin::where('name','messenger')->where('school_id',auth()->user()->school_id)->first();
            return view('backEnd.systemSettings.plugin_messenger_setting',$data);
            
        }
        catch(\Exception $e){

        }
    }

    public function messengerSettingUpdate(Request $request){

        $validator = Validator::make($request->all(), [
            'is_enable'         => 'required|numeric|in:0,1',
            'availability'      => 'required',
            'show_admin_panel'  => 'required|numeric|in:0,1',
            'show_website'      => 'required|numeric|in:0,1',
            'applicable_for'    => 'required',
            'position'          => 'required|string',
            'showing_page'      => 'required',
            'short_code'        => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
        
            Toastr::error($errorMessage, 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try{
            $setting = Plugin::where('name','messenger')->where('school_id',auth()->user()->school_id)->first();
            if($setting){
                $setting->is_enable = $request->is_enable; 
                $setting->availability = $request->availability; 
                $setting->show_admin_panel = $request->show_admin_panel ; 
                $setting->show_website = $request->show_website ; 
                $setting->applicable_for = $request->applicable_for ; 
                $setting->position = $request->position ; 
                $setting->showing_page = $request->showing_page ;
                $setting->short_code = $request->short_code ; 
                $setting->school_id = auth()->user()->school_id; 
                $setting->save();
                Toastr::success('Messenger settings Successfully Updated', 'Success'); 
                return redirect()->back();
            }
        }
        catch(\Exception $e){
            Toastr::error($e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

}