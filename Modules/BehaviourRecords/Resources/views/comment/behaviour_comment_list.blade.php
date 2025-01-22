@foreach ($incidentComments as $incidentComment)
    @if ($incidentComment->user_id != auth()->user()->id)
        @php
            if ($incidentComment->user->role_id == 2) {
                $profile = $incidentComment->user ? $incidentComment->user->student->student_photo : 'public/backEnd/img/admin/message-thumb.png';
            } elseif ($incidentComment->user->role_id == 3) {
                $profile = $incidentComment->user ? $incidentComment->user->parent->fathers_photo : 'public/backEnd/img/admin/message-thumb.png';
            } else {
                $profile = $incidentComment->user ? $incidentComment->user->staff->staff_photo : 'public/backEnd/img/admin/message-thumb.png';
            }
        @endphp
        <div class="profile-single-comment">
            <img src="{{ @$profile && file_exists(@$profile) ? asset($profile) : asset('public/backEnd/assets/img/avatar.png') }}"
                alt="profile-image">
            <div class="profile-comment">
                <div class="comment">{{ $incidentComment->comment }}</div>
                <p class="mb-0 mt-2 profile-comment-time">
                    {{ $incidentComment->user->roles->name }}-{{ $incidentComment->user->full_name }}-{{ dateConvert($incidentComment->created_at) }}
                </p>
            </div>
        </div>
    @else
        <div class="profile-single-comment reply">
            <img src="{{ @profile() && file_exists(@profile()) ? asset(profile()) : asset('public/backEnd/assets/img/avatar.png') }}"
                alt="profile-image">
            <div class="profile-comment">
                <div class="comment">{{ $incidentComment->comment }}</div>
                <p class="mb-0 mt-2 profile-comment-time">
                    {{ $incidentComment->user->roles->name }}-{{ $incidentComment->user->full_name }}-{{ dateConvert($incidentComment->created_at) }}
                </p>
            </div>
        </div>
    @endif
@endforeach
