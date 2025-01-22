<div class="{{$comment->status == 0 ? 'unapproveBgClass' : ''}}">
    <h5>
        {{$comment->user->full_name}} - {{$comment->user->roles->name}}
    </h5>
    <div>
        {{$comment->user->email}}
    </div>
</div>
