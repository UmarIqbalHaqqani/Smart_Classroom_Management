<table class="user_list_table table display nowrap" style="width:100%">
    <thead class="text-nowrap">
        <tr>
            <th>{{__('edulia.sl')}}</th>
            <th>{{__('edulia.image')}}</th>
            <th>{{__('edulia.name')}}</th>
            <th>{{__('edulia.role')}}</th>
            <th>{{__('edulia.department')}}</th>
            <th>{{__('edulia.designation')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($teachers as $key => $teacher)
            <tr>
                <td>{{$key+1}}</td>
                <td><img src="{{defaultUserLogo($teacher->staff_photo)}}" class="user_img" alt="{{$teacher->full_name}}"></td>
                <td><a href="{{route('frontend.staff-details',$teacher->id)}}" class="link_to_details">{{$teacher->full_name}}</a></td>
                <td>{{$teacher->roles->name}}</td>
                <td>{{$teacher->departments->name}}</td>
                <td>{{$teacher->designations->title}}</td>
            </tr>
        @endforeach
    </tbody>
</table>