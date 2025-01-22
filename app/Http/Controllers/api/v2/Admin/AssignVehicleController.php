<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\Http\Controllers\Controller;
use App\SmAcademicYear;
use Illuminate\Http\Request;

use App\SmRoute;
use App\SmAssignVehicle;
use Illuminate\Support\Facades\Auth;
use App\SmVehicle;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AssignVehicleController extends Controller
{
    public function assignToRoute()
    {
        $data['vehicles'] = SmVehicle::where('school_id', auth()->user()->school_id)->select('id', 'vehicle_no')->get();
        $data['routes'] = SmRoute::where('school_id', auth()->user()->school_id)->select('id', 'title')->get();
        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Vehicle & route list'
            ];
        }
        return response()->json($response);
    }

    public function storeAssign(Request $request)
    {
        $this->validate($request, [
            'route' => [
                'required',
                Rule::exists('sm_routes', 'id')->where('school_id', auth()->user()->school_id),
                Rule::unique('sm_assign_vehicles', 'route_id')->where('school_id', auth()->user()->school_id)
            ],
            'vehicles' => 'required|exists:sm_vehicles,id'
        ]);

        $assign_vehicle = new SmAssignVehicle();
        $assign_vehicle->route_id = $request->route;
        $assign_vehicle->vehicle_id = $request->vehicles;
        $assign_vehicle->school_id = auth()->user()->school_id;
        $assign_vehicle->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $assign_vehicle->save();

        if (!$assign_vehicle) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'The Vehicle assigned for the route successfully'
            ];
        }
        return response()->json($response);
    }

    public function assignList()
    {
        $assignList = SmAssignVehicle::with('route', 'vehicle')
            ->where('school_id', auth()->user()->school_id)
            ->get()
            ->map(function ($assign) {
                return [
                    'id'                => (int)$assign->id,
                    'route_name'        => (string)@$assign->route->title,
                    'vehicle_no'        => (string)@$assign->vehicle->vehicle_no,
                    'made_year'         => (int)@$assign->vehicle->made_year,
                    'vehicle_model'     => (string)@$assign->vehicle->vehicle_model,
                    'driver_name'       => (string)@$assign->vehicle->driver->full_name,
                    'driving_license'   => (string)@$assign->vehicle->driver->driving_license,
                    'driver_contact_no' => (string)@$assign->vehicle->driver->mobile
                ];
            });

        if (!$assignList) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $assignList,
                'message' => 'Assigned vehicle list'
            ];
        }
        return response()->json($response);
    }
}
