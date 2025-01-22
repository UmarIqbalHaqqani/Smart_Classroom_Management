@extends('backEnd.master')
@section('title')
@lang('system_settings.cron_job')
@endsection 
<style>
    .cron-job-instructions {
        font-size: 16px;
        line-height: 1.8;
    }
    .cron-command-box {
        background: #f9f9f9;
        border: 1px solid #ddd;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        color: #333;
    }
    .time-interval-box {
        background: #e9f7ef;
        border: 1px solid #d4edda;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        color: #155724;
        margin-top: 10px;
    }
    </style>
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.cron_job')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.cron_job')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12"> 
                <div class="white-box">
                    <div class="main-title">
                        <h3 class="mb-15">@lang('system_settings.cron_job_instructions')</h3>
                    </div>
                    <div class="cron-job-instructions">
                        <p class="mb-3">
                            <strong>@lang('system_settings.to_run_cron_jobs')</strong>
                        </p>
                        <div class="cron-command-box mb-4 text-center">
                            <code>
                                cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1
                            </code>
                        </div>
                        <p>
                            <strong>@lang('system_settings.in_cpanel_you_should_set_time_interval') (@lang('system_settings.every_ten_minutes')) :</strong> <code>*/10 * * * *</code> 
                        </p>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
