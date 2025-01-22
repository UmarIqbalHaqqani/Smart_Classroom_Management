<div class="container">
    <div class="row mt-5">
        <div class="col-lg-12">
            <div class="contacts_form">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('sub_heading') }}</span>
                    <h2>{{ pagesetting('heading') }}</h2>
                </div>
                {{-- <form action="#"> --}}
                <div class="contacts_form_flex">
                    <div class="input-control">
                        <input type="text" class="input-control-input"
                            placeholder='{{ pagesetting('name_placeholder') }}'
                            name="name" id="name" required>

                        <span id="nameError" class="text-danger ft"></span>
                    </div>
                    <div class="input-control">
                        <input type="tel" class="input-control-input"
                            placeholder='{{ pagesetting('phone_placeholder') }}'
                            name="phone" id="phone" required>

                        <span id="phoneError" class="text-danger ft"></span>
                    </div>
                </div>
                <div class="contacts_form_flex">
                    <div class="input-control">
                        <input type="email" class="input-control-input"
                            placeholder='{{ pagesetting('email_placeholder') }}' id="email"
                            name="email" required>

                        <span id="emailError" class="text-danger ft"></span>
                    </div>
                    <div class="input-control">
                        <input type="tel" class="input-control-input"
                            placeholder='{{ pagesetting('subject_placeholder') }}'
                            id="subject" name="subject"
                            required>

                        <span id="subjectError" class="text-danger ft"></span>
                    </div>
                </div>
                <div class="input-control">
                    <textarea class="input-control-input" name="message" id="message"
                        placeholder='{{ pagesetting('message_placeholder') }}' required></textarea>

                    <span id="messageError" class="text-danger ft"></span>
                </div>
                <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                <div class="input-control">
                    <input type="submit" id="click_btn" class="input-control-input"
                        value="{{ pagesetting('button_text') }}">
                </div>
                <p class=" mt-3 text-success"></p>
                <p class=" mt-3 text-danger"></p>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>
@pushonce(config('pagebuilder.site_script_var'))
    <script>
        $(document).ready(function() {
            $("#click_btn").click(function() {
                let url = $('#url').val();
                let name = $('#name').val();
                let phone = $('#phone').val();
                let email = $('#email').val();
                let subject = $('#subject').val();
                let message = $('#message').val();
                $.ajax({
                    url: url + '/' + 'edulia-send-message',
                    method: "GET",
                    data: {
                        name: name,
                        email: email,
                        phone: phone,
                        subject: subject,
                        message: message,
                    },
                    success: function(result) {
                            $('#name').val('');
                            $('#phone').val('');
                            $('#email').val('');
                            $('#subject').val('');
                            $('#message').val('');
                            $('.primary_input_field').removeClass('has-content');
                            $('.text-success').html('Email Sent Successfully');
                            $('.ft').html('');
                    },
                    error: function(xhr) {
                        $('#nameError').html(xhr.responseJSON.errors.name);
                        $('#phoneError').html(xhr.responseJSON.errors.phone);
                        $('#emailError').html(xhr.responseJSON.errors.email);
                        $('#subjectError').html(xhr.responseJSON.errors.subject);
                        $('#messageError').html(xhr.responseJSON.errors.message);
                    }
                })
            });
        });
    </script>
@endpushonce
