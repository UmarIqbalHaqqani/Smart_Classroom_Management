<x-table>
    <table id="table_id" class="table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>@lang('teacherEvaluation.staff_id')</th>
                <th>@lang('teacherEvaluation.teacher_name')</th>
                <th>@lang('teacherEvaluation.submitted_by')</th>
                <th>@lang('teacherEvaluation.class')(@lang('teacherEvaluation.section'))</th>
                <th>@lang('teacherEvaluation.rating')</th>
                <th>@lang('teacherEvaluation.comment')</th>
                <th>@lang('teacherEvaluation.status')</th>
                <th>@lang('teacherEvaluation.actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teacherEvaluations as $teacherEvaluation)
                @if ($teacherEvaluation->status == 1 && $approved_evaluation_button_enable == false)
                    <tr>
                        <td>{{ $teacherEvaluation->staff->id }}</td>
                        <td>{{ $teacherEvaluation->staff->full_name }}</td>
                        <td>
                            @if ($teacherEvaluation->role_id == 2)
                                {{ $teacherEvaluation->studentRecord->studentDetail->full_name }}(@lang('teacherEvaluation.student'))
                            @else
                                {{ $teacherEvaluation->studentRecord->studentDetail->parents->fathers_name }}(@lang('teacherEvaluation.parent'))
                            @endif
                        </td>
                        <td>{{ $teacherEvaluation->studentRecord->class->class_name }}({{ $teacherEvaluation->studentRecord->section->section_name }})
                        </td>
                        <td>
                            <div class="star-rating">
                                <input type="radio"
                                    id="5-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="5"
                                    {{ $teacherEvaluation->rating == 5 ? 'checked' : '' }}
                                    disabled />
                                <label for="5-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                    
                                <input type="radio"
                                    id="4-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="4"
                                    {{ $teacherEvaluation->rating == 4 ? 'checked' : '' }}
                                    disabled />
                                <label for="4-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                    
                                <input type="radio"
                                    id="3-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="3"
                                    {{ $teacherEvaluation->rating == 3 ? 'checked' : '' }}
                                    disabled />
                                <label for="3-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                    
                                <input type="radio"
                                    id="2-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="2"
                                    {{ $teacherEvaluation->rating == 2 ? 'checked' : '' }}
                                    disabled />
                                <label for="2-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                    
                                <input type="radio"
                                    id="1-star{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="1"
                                    {{ $teacherEvaluation->rating == 1 ? 'checked' : '' }}
                                    disabled />
                                <label for="1-star{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                            </div>
                        </td>
                        <td data-bs-toggle="tooltip" title="{{ $teacherEvaluation->comment }}">
                            {{ $teacherEvaluation->comment }}</td>
                        <td>
                            @if ($teacherEvaluation->status == 0)
                                <button
                                    class="primary-btn small bg-danger text-white border-0">@lang('teacherEvaluation.pending')</button>
                            @else
                                <button
                                    class="primary-btn small bg-success text-white border-0">@lang('teacherEvaluation.approved')</button>
                            @endif
                        </td>
                        <td>
                            <a class="primary-btn small fix-gr-bg"
                                href="{{ route('teacher-evaluation-approve-delete', $teacherEvaluation->id) }}"
                                style="padding: 0px 10px;!important"
                                data-bs-toggle="tooltip" title="Delete">&#x292C;</a>
                        </td>
                    </tr>
                @endif
                @if ($teacherEvaluation->status == 0 && $approved_evaluation_button_enable == true)
                    <tr>
                        <td>{{ $teacherEvaluation->staff->id }}</td>
                        <td>{{ $teacherEvaluation->staff->full_name }}</td>
                        <td>
                            @if ($teacherEvaluation->role_id == 2)
                                {{ $teacherEvaluation->studentRecord->studentDetail->full_name }}(@lang('teacherEvaluation.student'))
                            @else
                                {{ $teacherEvaluation->studentRecord->studentDetail->parents->fathers_name }}(@lang('teacherEvaluation.parent'))
                            @endif
                        </td>
                        <td>{{ $teacherEvaluation->studentRecord->class->class_name }}({{ $teacherEvaluation->studentRecord->section->section_name }})
                        </td>
                        <td>
                            <div class="star-rating">
                                <input type="radio"
                                    id="5-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="5"
                                    {{ $teacherEvaluation->rating == 5 ? 'checked' : '' }}
                                    disabled />
                                <label for="5-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                <input type="radio"
                                    id="4-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="4"
                                    {{ $teacherEvaluation->rating == 4 ? 'checked' : '' }}
                                    disabled />
                                <label for="4-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                <input type="radio"
                                    id="3-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="3"
                                    {{ $teacherEvaluation->rating == 3 ? 'checked' : '' }}
                                    disabled />
                                <label for="3-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                <input type="radio"
                                    id="2-stars{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="2"
                                    {{ $teacherEvaluation->rating == 2 ? 'checked' : '' }}
                                    disabled />
                                <label for="2-stars{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                                <input type="radio"
                                    id="1-star{{ $teacherEvaluation->id }}"
                                    name="rating{{ $teacherEvaluation->id }}" value="1"
                                    {{ $teacherEvaluation->rating == 1 ? 'checked' : '' }}
                                    disabled />
                                <label for="1-star{{ $teacherEvaluation->id }}"
                                    class="star">&#9733;</label>
                            </div>
                        </td>
                        <td data-bs-toggle="tooltip" title="{{ $teacherEvaluation->comment }}">
                            {{ $teacherEvaluation->comment }}</td>
                        <td>
                            <button
                                class="primary-btn small bg-danger text-white border-0">@lang('teacherEvaluation.pending')</button>
                        </td>
                        <td>
                            <a class="primary-btn small fix-gr-bg"
                                href="{{ route('teacher-evaluation-approve-submit', $teacherEvaluation->id) }}"
                                style="padding: 0px 10px;!important"
                                data-bs-toggle="tooltip" title="Approve">&#10003;</a>
                            <a class="primary-btn small fix-gr-bg"
                                href="{{ route('teacher-evaluation-approve-delete', $teacherEvaluation->id) }}"
                                style="padding: 0px 10px;!important"
                                data-bs-toggle="tooltip" title="Delete">&#x292C;</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</x-table>
