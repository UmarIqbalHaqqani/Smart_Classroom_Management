<?php

namespace App\Http\Controllers\api\v2\Admin\Chat;

use App\Models\User;
use App\Traits\FileStore;
use App\Traits\ImageStore;
use Illuminate\Http\Request;
use App\Events\GroupChatEvent;
use Modules\Chat\Entities\Group;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\Chat\Admin\GroupchatMessageResource;
use Illuminate\Validation\Rule;
use Modules\Chat\Entities\GroupUser;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Services\GroupService;
use Modules\Chat\Services\InvitationService;
use Modules\Chat\Services\ConversationService;
use Modules\Chat\Entities\GroupMessageRecipient;
use Modules\Chat\Notifications\GroupMessageNotification;
use Modules\Chat\Notifications\GroupCreationNotification;

class GroupChatController extends Controller
{

    public $invitationService, $groupService, $conversationService;


    public function __construct(InvitationService $invitationService, GroupService $groupService, ConversationService $conversationService)
    {
        $this->invitationService = $invitationService;
        $this->groupService = $groupService;
        $this->conversationService = $conversationService;
    }


    public function show(Group $group)
    {
        $group->load('users');

        $myGroups = $this->groupService->getAllGroup();
        $users = $this->invitationService->getAllConnectedUsers();
        $remainingUsers = $users->filter(function ($value, $key) use ($users, $group) {
            return !in_array($value->id, array_merge($group->users->pluck('id')->toArray(), [auth()->id()]));
        });

        $this->conversationService->readAllNotificationGroup($group->id);

        $group->load([
            'threads' => function ($query) {
                $query->latest();
                $query->take(20);
            },
            'threads.conversation.reply',
            'threads.conversation.forwardFrom',
            'threads.removeMessages.user',
            'threads.user.activeStatus', 'users'
        ]);

        foreach ($group->threads as $thread) {
            $contain = $thread->removeMessages->contains('user_id', auth()->id());
            if ($contain) {
                $thread->removedByMe = true;
            }
        }

        $only_threads = GroupMessageRecipient::with('conversation.reply', 'conversation.forwardFrom', 'removeMessages.user', 'user.activeStatus')
            ->where('group_id', $group->id)
            ->get();

        $single_threads = $only_threads->sortByDesc('created_at')->take(20)->map(function ($value, $key) {
            $contain = $value->removeMessages->contains('user_id', auth()->id());
            if ($contain) {
                $value->removedByMe = true;
            }

            return $value;
        });


        $myRole = GroupUser::where('user_id', auth()->id())->where('group_id', $group->id)->first()->role;
    }

    public function groupList()
    {
        $groups = $this->groupService->getAllGroup();
        $allGroup = [];
        $lastMessage = null;
        $lastMessageTime = null;
        foreach ($groups as $group) {
            $lastMessage = null;
            if($group->custom_order > 0){
                $groupMessage = GroupMessageRecipient::where('group_id', $group->id)
                ->orderBy('id', 'DESC')
                ->first();

                if ($groupMessage) {
                    // $conversation = Conversation::select('message', 'created_at')->find($groupMessage->conversation_id);
                    $lastMessage = @$groupMessage->conversation->message;
                    $lastMessageTime = @$groupMessage->conversation->create_at;
                }
            }

            $allGroup[] = [
                'group_id'          => (string)$group->id,
                'name'              => (string)$group->name,
                'image'             => $group->photo_url ? (string)asset($group->photo_url) : null,
                'last_message'      => (string)$lastMessage,
                'last_message_time' => $lastMessageTime ?? date('Y-m-d'),
            ];
        }

        if (empty($allGroup)) {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Chat group not found'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $allGroup,
                'message' => 'Chat group list'
            ];
        }
        return response()->json($response);
    }

    public function sendGroupMessage(Request $request)
    {
        info($request->all());
        if ($request->message == null && $request->file_attach == 'null') {
            return response()->json([
                'empty' => true
            ]);
        }

        $img_name = null;
        $original_name = null;
        $type = 0;

        if ($request->reply && ($request->reply == 'null' || $request->reply == null)) {
            $request->reply = null;
        } else {
            $request->reply = (int)$request->reply;
        }

        if ($request->hasFile('file_attach')) {
            $extension = $request->file('file_attach')->extension();
            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $img_name = ImageStore::saveImage($request->file('file_attach'));
            } else {
                $img_name = FileStore::saveFile($request->file('file_attach'));
            }
            $original_name = $request->file('file_attach')->getClientOriginalName();

            if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
                $type = 1;
            } elseif ($extension == 'pdf') {
                $type = 2;
            } elseif ($extension == 'doc' || $extension == 'docx') {
                $type = 3;
            } elseif ($extension == 'webm') {
                $type = 4;
            } elseif (in_array($extension, ['mp4', '3gp', 'mkv'])) {
                $type = 5;
            } else {
                $type = 0;
            }
        }

        $message = Conversation::create([
            'message' => $request->message,
            'file_name' => $img_name,
            'original_file_name' => $original_name,
            'message_type' => $type,
            'reply' => $request->reply,
        ])->load('reply');

        $group = Group::with('users')->select('id', 'name')->find($request->group_id);

        $thread = GroupMessageRecipient::create([
            'user_id' => $request->user_id,
            'conversation_id' => $message->id,
            'group_id' => $group->id
        ]);

        $thisGroup = Group::select('id', 'name')->find($group->id);

        try {
            foreach ($group->users as $user) {
                if ($user->id != auth()->id()) {
                    User::find($user->id)->notify(new GroupMessageNotification($thisGroup));
                }
            }
        } catch (\Exception $e) {
            //
        }

        $thisGroup = $thisGroup->toArray();

        $thisMessage = Conversation::select('id', 'message', 'status', 'message_type', 'file_name', 'original_file_name', 'reply', 'from_id', 'to_id', 'forward', 'deleted_by_to')->find($message->id)->toArray();

        $thisThread = GroupMessageRecipient::select('id', 'updated_at', 'user_id')->find($thread->id)->toArray();

        $user = auth()->user();

        $thisUser = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'avatar_url' => asset('/') . $user->avatar_url,
            'active_status' => $user->active_status,
        ];

        broadcast(new GroupChatEvent($thisGroup, $thisThread, $thisMessage, $thisUser))->toOthers();
        $group->load('threads.conversation.reply', 'threads.user');

        $resourceItems = [
            'thread' => $thread,
            'message' => $message,
        ];

        $data = new GroupchatMessageResource($resourceItems);

        $response = [
            'success' => true,
            'data' => [$data],
            'message' => 'Sent successfully.'
        ];

        return response()->json($response, 200);
    }

    public function groupMemberList(Request $request)
    {
        $members = GroupUser::where('group_id', $request->group_id)
            ->get()
            ->map(function ($value) {
                return [
                    'user_id' => (int)@$value->user->id,
                    'full_name' => (string)@$value->user->full_name,
                    'image' => @$value->user->avatar_url ? (string)asset($value->user->avatar_url) : null,
                ];
            });

        if (!$members) {
            $response = [
                'success' => false,
                'data'    => [],
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $members,
                'message' => 'Group member list'
            ];
        }
        return response()->json($response);
    }

    public function leaveGroup(Request $request)
    {
        $request->validate([
            'group_id' => ['required', Rule::exists('chat_group_users', 'group_id')->where('user_id', auth()->user()->id)]
        ], [
            'group_id.exists' => 'You have selected a wrong group'
        ]);
        $groupUser = GroupUser::where('user_id', auth()->user()->id)
            ->where('group_id', $request->group_id)
            ->first();
        $groupName = Group::select('name')->find($request->group_id)->name;
        $leave = $groupUser->delete();

        if (!$leave) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => "You left '$groupName' group successfully"
            ];
        }
        return response()->json($response);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'group_id' => ['required', Rule::exists('chat_group_users', 'group_id')->where('user_id', auth()->user()->id)]
        ], [
            'group_id.exists' => 'You have selected a wrong group'
        ]);

        $relatedGroup = GroupUser::where('group_id', $request->group_id)
            ->where('user_id', auth()->id())
            ->first();

        if ($relatedGroup->role != 1) {
            return response()->json(['notPermitted' => true]);
        }

        GroupUser::where('group_id', $request->group_id)->delete();
        $relatedGroup->delete();
        $deleteGroup = Group::where('id', $request->group_id)->where('school_id', auth()->user()->school_id)->delete();

        if (!$deleteGroup) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'The group is removed'
            ];
        }
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'user_id' => ['required', Rule::exists('users', 'id')->where('school_id', auth()->user()->school_id)]
        ]);

        if ($request->hasFile('group_photo')) {
            $request['photo_url'] = ImageStore::saveImage($request->group_photo);
        }

        $request->merge([
            'school_id' => auth()->user()->school_id,
            'created_by' => auth()->id(),
        ]);

        $group = Group::create($request->except('_token'));

        GroupUser::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'added_by' => auth()->id()
        ]);

        foreach ($request->user_id as $user) {
            GroupUser::create([
                'group_id' => $group->id,
                'user_id' => $user,
                'added_by' => auth()->id(),
                'role' => 3
            ]);
            try {
                User::find($user)->notify(new GroupCreationNotification($group));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $groupData = Group::find($group->id);

        $data = [
            'id' => (string)$groupData->id,
            'name' => (string)$groupData->name,
            'image' => $groupData->photo_url ? (string)asset($groupData->photo_url) : null
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
                'data'    => $data,
                'message' => $data['name'] . " created successfully"
            ];
        }
        return response()->json($response);
    }

    public function chatList(Request $request)
    {
        $data = GroupMessageRecipient::where('group_id', $request->group_id)
            ->get()
            ->map(function ($value) {
                if ($value->user_id == auth()->user()->id) {
                    $sender = true;
                    $receiver = false;
                } else {
                    $sender = false;
                    $receiver = true;
                }
                return [
                    'thread_id'             => (int)$value->id,
                    'message_id'            => (int)@$value->conversation->id,
                    'message'               => (string)@$value->conversation->message,
                    'status'                => (int)@$value->conversation->status,
                    'message_type'          => (int)@$value->conversation->message_type,
                    'file'                  => @$value->conversation->file_name ? (string)asset(@$value->conversation->file_name) : null,
                    'original_file_name'    => (string)@$value->conversation->original_file_name,
                    'forwarded'             => (bool)@$value->conversation->forward,
                    'reply'                 => (bool)@$value->conversation->replyId->message,
                    'reply_for'             => (int)@$value->conversation->replyId->message,
                    'sender'                => (bool)$sender,
                    'receiver'              => (bool)$receiver
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
                'message' => 'Group message list'
            ];
        }
        return response()->json($response);
    }

    public function removeMessage(Request $request)
    {
        $this->validate($request, [
            'thread_id' => 'required'
        ]);

        if ($this->conversationService->groupMessageDelete($request->all())) {
            $response = [
                'success' => true,
                'data' => null,
                'message' => 'Selected message is deleted successfully',
            ];
        } else {
            $response = [
                'success' => false,
                'data' => null,
                'message' => 'Operation failed',
            ];
        }

        return response()->json($response);
    }


    public function forward(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
            'message_id' => 'required',
            'message' => 'required',
            'user_id' => 'required',
        ]);
        $msg = Conversation::where('id', $request->message_id)->first();

        $message = Conversation::create([
            'from_id' => auth()->user()->id,
            'to_id' => $request->user_id,
            'message' => $msg->message,
            'file_name' => $msg->file_name ? asset($msg->file_name) : null,
            'original_file_name' => $msg->original_file_name,
            'message_type' => 0,
            'forward' => $request->message_id,
            'reply' => is_array($request->reply) ? $request->reply['id'] : $request->reply,
        ])->load('reply', 'forwardFrom');

        $group = Group::find($request->group_id);

        $thread = GroupMessageRecipient::create([
            'user_id' => auth()->user()->id,
            'conversation_id' => $message->id,
            'group_id' => $group->id
        ]);

        $thisGroup = Group::select('id', 'name')->find($group->id);

        foreach ($group->users as $user) {
            if ($user->id != auth()->id()) {
                User::find($user->id)->notify(new GroupMessageNotification($thisGroup));
            }
        }

        $thisGroup = $thisGroup->toArray();

        $thisMessage = Conversation::select('id', 'message', 'status', 'message_type', 'file_name', 'original_file_name', 'reply', 'from_id', 'to_id', 'forward', 'deleted_by_to')->find($message->id)->toArray();

        $thisThread = GroupMessageRecipient::select('id', 'updated_at', 'user_id')->find($thread->id)->toArray();

        $user = auth()->user();

        $thisUser = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'avatar_url' => asset('/') . $user->avatar_url,
            'active_status' => $user->active_status,
        ];

        broadcast(new GroupChatEvent($thisGroup, $thisThread, $thisMessage, $thisUser))->toOthers();
        $group->load('threads.conversation.reply', 'threads.conversation.forwardFrom', 'threads.user');

        $response = [
            'success' => true,
            'data' => null,
            'message' => 'Forwarded successfully.'
        ];

        return response()->json($response, 200);
    }

    public function fileList(Request $request)
    {
        $data = GroupMessageRecipient::where('group_id', $request->group_id)
            ->whereHas('conversation', function ($q) {
                $q->whereNotNull('file_name');
            })
            ->get()
            ->map(function ($value) {
                return [
                    'message_id' => (int)@$value->conversation->id,
                    'file' => @$value->conversation->file_name ? (string)asset(@$value->conversation->file_name) : null,
                    'original_file_name' => (string)@$value->conversation->original_file_name
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
                'message' => 'Group file list'
            ];
        }
        return response()->json($response);
    }

    public function removePeople(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:chat_group_users,user_id',
            'group_id'  => 'required|exists:chat_group_users,group_id'
        ]);
        $delete = GroupUser::where('user_id', $request->user_id)
            ->where('group_id', $request->group_id)
            ->first()
            ->delete();

        if (!$delete) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'The group member removed successfully'
            ];
        }
        return response()->json($response);
    }

    public function addPeople(Request $request)
    {
        $request->validate([
            'group_id' => ['required', Rule::exists('chat_group_users', 'group_id')->where('user_id', auth()->user()->id)],
            'user_id.*' => ['required', Rule::exists('users', 'id')->where('school_id', auth()->user()->school_id), Rule::unique('chat_group_users', 'user_id')->where('group_id', $request->group_id)],
        ], [
            'group_id.exists' => 'Invalid user',
            'user_id.exists' => 'Invalid user',
            'user_id.unique' => 'Allready group member',
        ]);

        $group = Group::find($request->group_id);

        foreach ($request->get('user_id', []) as $key => $user_id) {
            GroupUser::updateOrCreate([
                'group_id' => $group->id,
                'user_id' => $user_id,
                'added_by' => auth()->id()
            ]);
            User::find($user_id)->notify(new GroupCreationNotification($group));
        }

        $response = [
            'success' => true,
            'data' => null,
            'message' => 'Member added successfully'
        ];

        return response()->json($response);
    }
}
