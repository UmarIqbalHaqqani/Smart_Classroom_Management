<?php

namespace App\Http\Controllers\api\v2\Admin\Chat;

use App\Models\User;
use App\Events\ChatEvent;
use App\Traits\FileStore;
use App\Traits\ImageStore;
use Illuminate\Http\Request;
use App\Events\GroupChatEvent;
use Modules\Chat\Entities\Group;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\Chat\Admin\ChatUserListResource;
use App\Http\Resources\v2\Chat\Admin\SingleChatResource;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Validation\Rule;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Invitation;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Services\GroupService;
use Modules\Chat\Services\InvitationService;
use Modules\Chat\Services\ConversationService;
use Modules\Chat\Entities\GroupMessageRecipient;
use Modules\Chat\Notifications\MessageNotification;
use Modules\Chat\Notifications\GroupMessageNotification;

class AdminChatController extends Controller
{
    use ImageStore;

    public $groupService, $invitationService, $conversationService;

    // protected $invitation;

    public function __construct(InvitationService $invitationService, GroupService $groupService, ConversationService $conversationService)
    {
        $this->groupService = $groupService;
        $this->invitationService = $invitationService;
        $this->conversationService = $conversationService;
    }

    public function fileHandle(Request $request): array
    {
        $img_name = null;
        $original_name = null;
        $type = 0;

        if ($request->hasFile('file_attach')) {
            $extension = $request->file('file_attach')->extension();
            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $img_name = ImageStore::saveImage($request->file('file_attach'));
            } else {
                $img_name = ImageStore::saveFile($request->file('file_attach'));
            }
            $original_name = $request->file('file_attach')->getClientOriginalName();

            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $type = 1;
            } elseif ($extension == 'pdf') {
                $type = 2;
            } elseif ($extension == 'doc' || $extension == 'docx') {
                $type = 3;
            } elseif ($extension == 'webm' || $extension == 'oga') {
                $type = 4;
            } elseif (in_array($extension, ['mp4', '3gp', 'mkv'])) {
                $type = 5;
            } else {
                $type = 0;
            }
        }
        return array($img_name, $original_name, $type);
    }

    public function replyValidation(Request $request): void
    {
        if ($request->reply && ($request->reply == 'null' || $request->reply == null)) {
            $request->reply = null;
        } else {
            $request->reply = (int)$request->reply;
        }
    }

    public function userList(Request $request)
    {
        $users = $this->invitationService->getAllConnectedUsers();
        $data = ChatUserListResource::collection($users);

        if ($data->isEmpty()) {
            $status = 200;
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Users not found'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Connected user list'
            ];
            $status = 200;
        }
        return response()->json($response, $status);
    }



    public function sendMessage(Request $request)
    {
        $limit = ((int) app('general_settings')->get('chat_file_limit') * 1024) ?? 204800;
        $this->validate($request, [
            'from_user_id' => 'required',
            'to_user_id' => ['required', Rule::exists('users', 'id')->where('school_id', auth()->user()->school_id)],
            'file_attach' => "max:$limit"
        ]);

        if ($request->message == null && $request->file_attach == 'null') {
            return response()->json([
                'empty' => true
            ]);
        }

        if ($request->message == 'null') {
            return response()->json([
                'empty' => true
            ]);
        }

        list($img_name, $original_name, $type) = $this->fileHandle($request);
        $this->replyValidation($request);

        $message = Conversation::create([
            'from_id' => auth()->id(),
            'to_id' => $request->to_user_id,
            'message' => $request->message,
            'file_name' => $img_name,
            'original_file_name' => $original_name,
            'message_type' => $type,
            'reply' => $request->reply,
        ])->load('reply', 'forwardFrom');

        User::find($request->to_user_id)->notify(new MessageNotification($message));
        broadcast(new ChatEvent($message))->toOthers();

        // Start        
        $user = User::find($request->to_user_id);

        if (chatOpen() || invitationRequired()) {
            $invitation = Invitation::where('to', auth()->id())->where('from', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('from', auth()->id());
                    $query->where('to', $user->id);
                })->first();

            if ($invitation) {
                $invitation->status = 1;
                $invitation->save();
            }
        }

        if (!$user->connectedWithLoggedInUser()) {
            $invitation = $this->invitationService->invitationCreate($request->to_user_id, 1);
        }
        //end

        $data = new SingleChatResource($message);

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
                'message' => 'Message send successful'
            ];
        }
        return response()->json($response);
    }



    public function messages(Request $request)
    {
        $this->validate($request, [
            'to_user_id' => 'required'
        ]);

        $messages = Conversation::whereHas('fromUser', function ($u) use ($request) {
            $u->where('id', auth()->user()->id)->orWhere('id', $request->to_user_id);
        })->whereHas('toUser', function ($us) use ($request) {
            $us->where('id', $request->to_user_id)->orWhere('id', auth()->user()->id);
        })->get();

        $data = SingleChatResource::collection($messages);

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
                'message' => 'Message list'
            ];
        }
        return response()->json($response);
    }

    public function deleteMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:chat_conversations,id'
        ]);

        $conversation = Conversation::where('id', $request->message_id)
            ->when(auth()->user()->id, function ($q) {
                $q->where('from_id', auth()->user()->id)
                    ->orWhere('to_id', auth()->user()->id);
            })
            ->first();

        if ($conversation) {
            if (file_exists($conversation->file_name)) {
                unlink($conversation->file_name);
            }
            Conversation::destroy($request->message_id);
            $response = [
                'success' => true,
                'data' => null,
                'messages' => 'The message deleted successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'data' => null,
                'messages' => 'Operation failed'
            ];
        }

        return response()->json($response);
    }

    public function forward(Request $request)
    {
        if (auth()->user()->id != $request->to_user_id) {
            $this->validate($request, [
                'to_user_id' => 'required',
                'message_id' => 'required'
            ]);

            $msg = Conversation::where('id', $request->message_id)->first();

            $message = Conversation::create([
                'from_id' => auth()->user()->id,
                'to_id' => $request->to_user_id,
                'message' => $msg->message,
                'file_name' => $msg->file_name ?? null,
                'original_file_name' => $msg->original_file_name ?? null,
                'message_type' => 0,
                'forward' => $request->message_id,
                'reply' => 0
            ])->load('reply', 'forwardFrom');

            User::find($request->to_user_id)->notify(new MessageNotification($message));
            broadcast(new ChatEvent($message))->toOthers();

            $response = [
                'success' => true,
                'data' => null,
                'message' => 'Message forwarded successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'data' => null,
                'message' => 'You cannot forward to yourself'
            ];
        }

        return response()->json($response);
    }

    public function fileList(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required'
        ]);

        $data = Conversation::whereNotNull('file_name')
            ->where(function ($q) use ($request) {
                $q->where('from_id', auth()->user()->id)
                    ->where('to_id', $request->user_id)
                    ->orWhere('to_id', auth()->user()->id)
                    ->orWhere('from_id', $request->user_id);
            })
            ->get()
            ->map(function ($value) {
                return [
                    'message_id'            => (int)$value->id,
                    'file'                  => $value->file_name ? (string)asset($value->file_name) : null,
                    'original_file_name'    => (string)$value->original_file_name
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
                'message' => 'The user file list'
            ];
        }
        return response()->json($response);
    }

    public function reply(Request $request)
    {
        if (auth()->user()->id != $request->to_user_id) {
            $this->validate($request, [
                'to_user_id' => 'required',
                'message_id' => 'required'
            ]);

            $msg = Conversation::where('id', $request->message_id)->first();

            $message = Conversation::create([
                'from_id' => auth()->user()->id,
                'to_id' => $request->to_user_id,
                'message' => $msg->message,
                'file_name' => $msg->file_name ?? null,
                'original_file_name' => $msg->original_file_name,
                'message_type' => 0,
                'forward' => $request->message_id,
                'reply' => 0
            ])->load('reply', 'forwardFrom');

            User::find($request->to_user_id)->notify(new MessageNotification($message));
            broadcast(new ChatEvent($message))->toOthers();

            $response = [
                'success' => true,
                'data' => null,
                'message' => 'Operation successfull.'
            ];
        } else {
            $response = [
                'success' => false,
                'data' => null,
                'message' => 'You cannot forward to yourself.'
            ];
        }

        return response()->json($response);
    }
}
