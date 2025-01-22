@if(( $graduates)) 
    <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('alumni::al.graduate_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <x-table>
                        <table class="school-table school-table-data" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('student.admission_no')</th>
                                    <th>@lang('common.name')</th>
                                    @if (moduleStatusCheck('University'))
                                        <th>@lang('university::un.session')</th>
                                        <th>@lang('alumni::al.fac_dept')</th>
                                    @else
                                        <th>@lang('common.class')</th>
                                        <th>@lang('common.section')</th>
                                    @endif
                                    <th>@lang('university::un.mobile')</th>
                                    <th>@lang('common.gender')</th>
                                    <th>@lang('student.date_of_birth')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($graduates as $graduate)
                                    <tr>
                                        <td><a href="{{route('student_view',$graduate->student->id)}}" target="_blank">{{@$graduate->student->admission_no}}</a></td>
                                        <td><a href="{{route('student_view',$graduate->student->id)}}" target="_blank">{{@$graduate->student->full_name}}</a></td>
                                        @if (moduleStatusCheck('University'))
                                            <td>{{@$graduate->unSession->name}}</td>
                                            <td>{{@$graduate->unFaculty->name}} ({{@$graduate->unDepartment->name}})</td>
                                        @else
                                            <td>{{@$graduate->smClass->class_name}}</td>
                                            <td>{{@$graduate->section->section_name}}</td>
                                        @endif
                                        <td>{{@$graduate->student->mobile}}</td>
                                        <td>{{@$graduate->student->gender->base_setup_name}}</td>
                                        <td>{{@dateConvert($graduate->student->date_of_birth)}}</td>
                                        
                                        <td valign="top">
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{route('alumni.view-transcript', [$graduate->id])}}" target="_blank">
                                                        @lang('alumni::al.view_transcript')
                                                    </a>
                                                    <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('university::un.add_alumni_information')" href="{{route('alumni.add-alumni-detail',[$graduate->id])}}" data-modal-size="modal-lg" title="@lang('alumni::al.add_alumni_information')" href="{{route('alumni.add-alumni-detail',[$graduate->id])}}">@lang('alumni::al.add_alumni_information')</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
@endif 