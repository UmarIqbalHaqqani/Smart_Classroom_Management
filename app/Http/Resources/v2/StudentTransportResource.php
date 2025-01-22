<?php

namespace App\Http\Resources\v2;

use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StudentTransportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this ?? auth()->user();
        $student_detail = SmStudent::where('user_id', $user->id)->first();
        if (@$student_detail->route_list_id == @$this->route->id && @$student_detail->vechile_id == @$this->vehicle->id) {
            $status = 'Assigned';
        } else {
            $status = '';
        }

        return [
            'id'                => (int)$this->id,
            'route'             => (string)@$this->route->title,
            'vehicle'           => (string)@$this->vehicle->vehicle_no,
            'status'            => (string)$status,
            'vehicle_model'     => (string)$this->vehicle_model,
            'made'              => (int)$this->made_year,
            'driver_name'       => (string)@$this->vehicle->driver->full_name,
            'driver_license'    => (string)@$this->vehicle->driver->driving_license,
            'driver_contact'    => (string)@$this->vehicle->driver->emergency_mobile,
            'dormitory'         => (string)@$this->dormitory->dormitory_name,
        ];
    }
}
