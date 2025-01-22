<?php

namespace App\Http\Controllers\api\v2\Admin\Chat;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\Chat\Admin\ChatUserListResource;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Services\UserService;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Entities\Status;

class AdminUserServiceController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function search(Request $request)
    {
        $keywords = $request->keywords;
        if ($keywords) {
            $users = $this->userService->search($keywords)->load('activeStatus');
        } else {
            $users = [];
        }

        $data = ChatUserListResource::collection($users);

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
                'message' => 'Search user successful'
            ];
        }
        return response()->json($response);
    }

    public function changeStatus(Request $request)
    {
        $type = $request->status;

        userStatusChange(auth()->id(), $type);

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Status changed successfully'
        ];
        return response()->json($response);
    }

    public function blockAction(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
        ]);

        $user = $request->user_id;
        $type = $request->type;

        $this->userService->blockAction($type, $user);

        $response = [
            'success' => true,
            'data' => null,
            'messege' => 'The user is blocked for you'
        ];

        return response()->json($response);
    }

    public function blockedUsers()
    {
        $data = $this->userService->allBlockedUsers()->map(function ($value) {
            return [
                'user_id'       => (int)$value->id,
                'full_name'     => (string)$value->full_name,
                'image'         => $value->avatar_url ? (string)asset($value->avatar_url) : null,
                'active_status' => (int)$value->active_status
            ];
        });

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
                'message' => 'Blocked user list'
            ];
        }
        return response()->json($response);
    }

    public function chatStatus()
    {
        $status = Status::where('user_id', auth()->user()->id)
            ->select('status')
            ->first();

        switch ($status->status) {
            case 0:
                $data = [
                    'status' => (string)'INACTIVE',
                    'color' => (string)'0xFFE1E2EC'
                ];
                break;
            case 1:
                $data = [
                    'status' => (string)'ACTIVE',
                    'color' => (string)'0xFF12AE01'
                ];
                break;
            case 2:
                $data = [
                    'status' => (string)'AWAY',
                    'color' => (string)'0xFFF99F15'
                ];
                break;
            case 3:
                $data = [
                    'status' => (string)'BUSY',
                    'color' => (string)'0xFFF60003'
                ];
                break;
        }

        $data['status_info'] = [
            [
                'key' => (int)0,
                'name' => (string)'INACTIVE',
                'color' => (string)'0xFFE1E2EC'
            ],
            [
                'key' => (int)1,
                'name' => (string)'ACTIVE',
                'color' => (string)'0xFF12AE01'
            ],
            [
                'key' => (int)2,
                'name' => (string)'AWAY',
                'color' => (string)'0xFFF99F15'
            ],
            [
                'key' => (int)3,
                'name' => (string)'BUSY',
                'color' => (string)'0xFFF60003'
            ],
        ];

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [$data],
                'message' => 'Active status'
            ];
        }
        return response()->json($response);
    }
}
