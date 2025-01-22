<?php

namespace App\Http\Controllers\Admin\Communicate;

use App\GlobalVariable;
use App\SmEvent;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Yajra\DataTables\Facades\DataTables;
use Modules\RolePermission\Entities\InfixRole;
use App\Http\Requests\Admin\Communicate\EventRequest;
use App\Traits\NotificationSend;

class SmEventController extends Controller
{
    use NotificationSend;

    public function index()
    {
        try {
            $data = $this->indexData();
            return view('backEnd.events.eventsList', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(EventRequest $request)
    {
        try {
            $destination ='public/uploads/events/';

            $events = new SmEvent();
            $events->event_title = $request->event_title;
            $events->role_ids = json_encode($request->role_ids);
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->url = $request->url;
            $events->created_by = auth()->user()->id;
            $events->uplad_image_file =fileUpload($request->upload_file_name,$destination);
            $events->school_id = auth()->user()->school_id;
            if(moduleStatusCheck('University')){
                $events->un_academic_id = getAcademicId();
            }else{
                $events->academic_id = getAcademicId();
            }
            $events->save();
            $data['event'] = $request->event_title;
            foreach($request->role_ids as $role_id){
                $userIds = User::where('role_id', $role_id)->where('active_status', 1)->pluck('id')->toArray();
                if($role_id == 4){
                    $this->sent_notifications('Event', $userIds, $data, ['Teacher']);
                }
                if($role_id == 3){
                    $this->sent_notifications('Event', $userIds, $data, ['Parent']);
                }
                if($role_id == 2){
                    $this->sent_notifications('Event', $userIds, $data, ['Student']);
                }
                if($role_id == GlobalVariable::isAlumni()){
                    $this->sent_notifications('Event', $userIds, $data, ['Alumni']);
                }
            }
            if($request->data_type == 'ajax'){
                return response()->json($events);
            }else{
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            if($request->data_type == 'ajax'){
                return response()->json(['error' => $e]);
            }else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    }

    public function edit($id)
    {
        try {
            $data = $this->indexData();
            $data['editData'] = SmEvent::find($id);
            return view('backEnd.events.eventsList', $data);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(EventRequest $request, $id)
    {
        try {
            $destination ='public/uploads/events/';

            $events = SmEvent::find($id);
            $events->event_title = $request->event_title;
            $events->role_ids = json_encode($request->role_ids);
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->url = $request->url;
            $events->updated_by = auth()->user()->id;
            $events->uplad_image_file = fileUpdate($events->uplad_image_file,$request->upload_file_name,$destination);
            $events->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('event');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteEvent($id)
    {
        try {
            $data = SmEvent::find($id);
            if ($data->uplad_image_file != "") {
                $path = $data->uplad_image_file;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $data->delete();
            Toastr::success('Operation Successful', 'Success');
            return redirect('event');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteEventView(Request $request, $id)
    {
        try {
            return view('backEnd.events.deleteEventView', compact('id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function getAllEventList()
    {
        try {
            $events = SmEvent::when(auth()->user()->roles->id != 1, function($s){
                $s->whereJsonContains('role_ids', (string) auth()->user()->roles->id);
            });
            return DataTables::of($events)
            ->addIndexColumn()
            ->addColumn('title', function ($event) {
                return $event->event_title;
            })
            ->addColumn('name', function ($event) {
                $roleName = [];
                if($event->role_ids){
                    foreach(json_decode($event->role_ids)  as $roleData){
                    $roleName[] = $this->roleName($roleData)->name;
                    }
                }
                return $roleName;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('event_title', 'like', '%' . $keyword . '%')
                      ->orWhere('event_location', 'like', '%' . $keyword . '%');
            })
            ->addColumn('date', function ($event) {
                return dateConvert($event->from_date). '-' . dateConvert($event->to_date);
            })
            ->addColumn('location', function ($event) {
                return $event->event_location;
            })
            ->addColumn('action', function ($event) {
                return view('backEnd.events._eventAction', compact('event'));
            })
            ->rawColumns(['action'])
            ->toJson();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    private function indexData(){
        $data['events'] = SmEvent::get();
        $data['roles'] = InfixRole::where('is_saas',0)->where(function ($q) {
                            $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
                        })
                        ->whereNotIn('id', [1])
                        ->get();
        return $data;
    }

    private function roleName($roleId){
        return InfixRole::find($roleId, ['name']);
    }

    public function newDesign(){
        return view('backEnd.events.newDesign');
    }
}