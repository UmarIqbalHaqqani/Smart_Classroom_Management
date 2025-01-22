@push('css')
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/backEnd/assets/vendors/css/customFullCalendar.css') }}" />
    <style>
        .color-input {
            height: 50px;
            padding: 0px !important;
            border: none !important;
            background: transparent;
        }

        @media(max-width: 576px) {
            .fc-daygrid-day-frame {
                padding-bottom: 20px;
            }

            a.fc-daygrid-more-link.fc-more-link {
                font-size: 8px;
            }

            .fc-button-group {
                margin-bottom: 20px
            }
        }
    </style>
@endpush
@include('backEnd.partials.multi_select_js')
@push('script')
    <script src="{{ asset('public/backEnd/') }}/full_calendar/js/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('academicCalendar');
            var eventsData = @json($events);
            var system_url = $('#system_url').val();
            @php
                $is_alumni = App\GlobalVariable::isAlumni();
            @endphp
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prevYear,prev,next,nextYear today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay,listMonth'
                },
                initialDate: '{{ \Carbon\Carbon::now()->format('Y-m-d') }}',
                navLinks: true, // can click day/week names to navigate views
                @if (userPermission('event-store') && auth()->user()->role_id != $is_alumni)
                    editable: true,
                    selectable: true,
                @endif
                dayMaxEvents: true, // allow "more" link when too many events
                events: eventsData,

                select: function(start, end, allDays) {
                    var startDate = start.start;
                    var endDate = start.end;
                    $('#calendarStartDate').val(moment(startDate).format('YYYY-MM-DD'));
                    $('#currentDate').html('(' + formatDate(startDate) + ')');
                    errorDataShow(true, null);

                    $('#addEventOnCalendar').modal('show');
                },
                eventClick: function(event, jsEvent, view) {
                    $('#downloadEventFile').addClass('d-none');
                    $('#urlEventData').addClass('d-none');
                    var startDate = formatDate(event.event.start);
                    var endDate = formatDate(event.event.extendedProps.endDate);
                    var startEndDate = startDate + ' {{ __('common.to') }} ' + endDate;
                    $('#startAndEndDate').html(startEndDate);

                    if (event.event.extendedProps.type == 'admission_query') {
                        $('#AQname').html(event.event.extendedProps.name);
                        $('#AQphone').html(event.event.extendedProps.phone);
                        $('#AQaddress').html(event.event.extendedProps.address);
                        $('#AQemail').html(event.event.extendedProps.email);
                        $('#AQdate').html(formatDate(event.event.start));
                        $('#admissionQueryUrl').attr('href', event.event.extendedProps.route);

                        $('.commonModalContent').addClass('d-none');
                        $('.admissionQueryModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'homework') {
                        $('#Hdescription').html(event.event.extendedProps.description);
                        $('#Hclass').html(event.event.extendedProps.class);
                        $('#Hsection').html(event.event.extendedProps.section);
                        $('#Hsubject').html(event.event.extendedProps.subject);
                        $('#Hdate').html(formatDate(event.event.start));
                        $('#homeworkRoute').attr('href', event.event.extendedProps.route);

                        $('.commonModalContent').addClass('d-none');
                        $('.homeworkModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'study_material') {
                        $('#SMtitle').html(event.event.extendedProps.content_title);
                        $('#SMtype').html(event.event.extendedProps.content_type);
                        $('#SMavailable').html(event.event.extendedProps.avaiable);
                        $('#SMdescription').html(event.event.extendedProps.description);
                        $('#SMdate').html(formatDate(event.event.start));

                        $('.commonModalContent').addClass('d-none');
                        $('.studyMaterialModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'event') {
                        $('#Etitle').html(event.event.extendedProps.content_title);
                        $('#Edescription').html(event.event.extendedProps.description);
                        $('#Elocation').html(event.event.extendedProps.location);
                        $('#Esdate').html(formatDate(event.event.start));
                        $('#Eedate').html(formatDate(event.event.extendedProps.endDate));
                        if (event.event.extendedProps.image) {
                            $('#eventFile').removeClass('d-none');
                            $('#eventFile').attr('href','/' + event.event.extendedProps
                                .image);
                        }
                        if (event.event.extendedProps.link) {
                            $('#eventLink').removeClass('d-none');
                            $('#eventLink').attr('href', event.event.extendedProps.link);
                        }

                        $('.commonModalContent').addClass('d-none');
                        $('.eventModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'holiday') {
                        $('#HDtitle').html(event.event.extendedProps.title_content);
                        $('#HDdescription').html(event.event.extendedProps.description);
                        $('#HDsdate').html(formatDate(event.event.start));
                        $('#HDedate').html(formatDate(event.event.extendedProps.endDate));
                        if (event.event.extendedProps.image) {
                            $('#holidayFile').removeClass('d-none');
                            $('#holidayFile').attr('href', system_url + '/' + event.event.extendedProps
                                .image);
                        }

                        $('.commonModalContent').addClass('d-none');
                        $('.holidayModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'exam') {
                        $('#EMname').html(event.event.extendedProps.exam_term);
                        $('#EMclass').html(event.event.extendedProps.class);
                        $('#EMsection').html(event.event.extendedProps.section);
                        $('#EMsubject').html(event.event.extendedProps.subject);
                        $('#EMteacher').html(event.event.extendedProps.teacher);
                        $('#EMroom').html(event.event.extendedProps.room);
                        $('#EMdate').html(event.event.extendedProps.endDate);
                        $('#EMstarttime').html(event.event.extendedProps.start_time);
                        $('#EMendtime').html(event.event.extendedProps.end_time);


                        $('.commonModalContent').addClass('d-none');
                        $('.examModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'notice_board') {
                        $('#NBtitle').html(event.event.extendedProps.title_content);
                        $('#NBdescription').html(event.event.extendedProps.notice_message);
                        $('#NBinform').html(event.event.extendedProps.inform_to);
                        $('#NBdate').html(formatDate(event.event.extendedProps.endDate));

                        $('.commonModalContent').addClass('d-none');
                        $('.noticeBoardModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'online_exam') {
                        $('#OEtitle').html(event.event.extendedProps.title_content);
                        $('#OEclass').html(event.event.extendedProps.class);
                        $('#OEsection').html(event.event.extendedProps.section);
                        $('#OEsubject').html(event.event.extendedProps.subject);
                        $('#OEstartdate').html(formatDate(event.event.start));
                        $('#OEenddate').html(formatDate(event.event.extendedProps.endDate));
                        $('#OEstarttime').html(event.event.extendedProps.start_time);
                        $('#OEendtime').html(event.event.extendedProps.end_time);

                        $('.commonModalContent').addClass('d-none');
                        $('.onlineExamModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'lesson_plan') {
                        $('#LPclass').html(event.event.extendedProps.class);
                        $('#LPsection').html(event.event.extendedProps.section);
                        $('#LPsubject').html(event.event.extendedProps.subject);
                        $('#LPteacher').html(event.event.extendedProps.teacher);
                        $('#LPdate').html(formatDate(event.event.start));

                        $('.commonModalContent').addClass('d-none');
                        $('.lessonPlanModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'leave') {
                        $('#Lname').html(event.event.extendedProps.name);
                        $('#Lreason').html(event.event.extendedProps.reason);
                        $('#Lstartdate').html(formatDate(event.event.start));
                        $('#Lenddate').html(formatDate(event.event.extendedProps.endDate));

                        $('.commonModalContent').addClass('d-none');
                        $('.leaveModal').removeClass('d-none');
                    } else if (event.event.extendedProps.type == 'library') {
                        $('#Lbooktitle').html(event.event.extendedProps.book_name);
                        $('#Lduedate').html(formatDate(event.event.start));

                        $('.commonModalContent').addClass('d-none');
                        $('.libraryModal').removeClass('d-none');
                    }
                    $('#descriptionModal').modal('show');
                },
            });
            calendar.render();
        });

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            var dayName = d.toLocaleString('en-us', {
                weekday: 'long'
            });
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [dayName, day, month, year].join('- ');
        }

        function errorDataShow(blank, xhr) {
            if (blank) {
                $('#error_event_title').html('');
                $('#error_role').html('');
                $('#error_event_location').html('');
                $('#error_description').html('');
                $('#error_url').html('');
            } else {
                $('#error_event_title').html(xhr.responseJSON.errors.event_title);
                $('#error_role').html(xhr.responseJSON.errors.role_ids);
                $('#error_event_location').html(xhr.responseJSON.errors.event_location);
                $('#error_description').html(xhr.responseJSON.errors.event_des);
                $('#error_url').html(xhr.responseJSON.errors.url);
            }
        }

        $('#saveButtonForAddEvent').click(function(e) {
            e.stopPropagation();
            let strtDate = $('#calendarStartDate').val();
            var event_title = $("input[name=event_title]").val();
            var role_ids = $('#selectMultiUsers').val();
            var event_location = $("input[name=event_location]").val();
            var event_des = $('#event_desData').val();
            var url = $('#event_urlData').val();
            var from_date = strtDate;
            var to_date = strtDate;
            var data_type = 'ajax';
            console.log(event_title);
            $.ajax({
                url: '{{ route('event') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    event_title,
                    role_ids,
                    event_location,
                    event_des,
                    url,
                    from_date,
                    to_date,
                    data_type
                },
                success: function(response) {
                    $('#addEventOnCalendar').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    errorDataShow(null, xhr);
                }
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $('.calenderSettingsJs').on('click', function(e) {
                e.preventDefault();
                $('.showAndHideSettings').slideToggle();
            })
        });
    </script>
@endpush
