@extends('backEnd.master')
@section('title') 
    @lang('communicate.event_list')
@endsection

@push('css')
<style>
    .create-new-bundle{
        background: #ffffff;
        border: 2px solid lightgray;
        border-radius: 5px;
        min-height: 100%;
    }

    .create-new-bundle p{
        color: gray;
        font-size: 18px
    }
    .create-new-bundle i{
        border: 2px solid lightgray;
        border-radius: 50%;
        height: 30px;
        width: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: gray;
        font-weight: bold
    }

    .single-bundle-item{
        background: #ffffff;
        border: 2px solid lightgray;
        border-radius: 5px;
    }

    .single-bundle-item .bundle-image{
        height: 200px;
        object-fit: cover;
        width: 100%;
        border-radius: 5px 5px 0 0;
    }
    .single-bundle-item .bundle-info{
        gap: 10px;
    }
    .single-bundle-item .bundle-info .bundle-name{
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 14px;
        color: gray;
    }

    .single-bundle-item .bundle-info .bundle-name:hover{
        color: red;
    }
    .single-bundle-item .bundle-data{
        border-top: 2px solid lightgray;
        font-size: 13px;
        color: gray;
    }

    .bundle-action-dropdown{
        cursor: pointer;
    }
    .bundle-action-dropdown .dropdown-menu{
        min-width: auto!important;
    }
    .bundle-action-dropdown a{
        padding: 4px 10px;
    }

    .bundle-action-dropdown div[aria-expanded="true"] svg{
        color: skyblue;
    }

    .bundle-image-container{
        position: relative;
    }

    .bundle-image-plus-icon{
        position: absolute;
        right: 10px;
        top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #ffffff;
        border-radius: 5px;
        padding: 5px;
    }
    .bundle-image-plus-icon i{
        background: green;
        color: #ffffff;
        border-radius: 50%;
        padding: 2px;
    }
</style>
@endpush
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.event_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.event_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid bg-white p-5 rounded">
        {{-- Search Section:Start --}}

        <div class="row justify-content-end mb-4">
            <div class="input-group col-md-3">
                <input type="text" class="form-control" placeholder="Find a bundle">
    
                <div class="input-group-prepend">
                    <a class="input-group-text btn" href="#">
                        <i class="ti-search" style="font-size: 13px;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Search Section:End --}}

        {{-- Bundle section:Start --}}
        <div class="row">
            {{-- Create new bundle:Start --}}
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 px-2">
                    <a href="#" class="create-new-bundle d-flex flex-column justify-content-center align-items-center">
                        <i class="ti-plus"></i>
                        <p class="mb-0">Create New Bundle</p>
                    </a>
            </div>
            {{-- Create new bundle:End --}}

            {{-- Single bundle item:Start --}}
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 px-2">
                <div class="single-bundle-item">
                    <div class="bundle-image-container">
                        <img src="https://picsum.photos/500?random=1" class="bundle-image" alt="bundle image">

                        <div class="bundle-image-plus-icon">
                            <i class="ti-plus"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-info p-2">
                        <a class="bundle-name" href="#">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perspiciatis, quos.</a>

                        <div class="dropdown bundle-action-dropdown">
                            <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                  </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#"><i class="ti-pencil"></i> Edit</a>
                              <a class="dropdown-item" href="#"><i class="ti-layers"></i> Clone</a>
                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>
                            </div>
                          </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-data p-2">
                        <div class="bundle-price">
                            <span>$52.33</span>
                            <span>Sales</span>
                        </div>

                        <div class="bundle-enrollment-count">
                            <span>9</span>
                            <span>Enrollments</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Single bundle item:End --}}

            {{-- Single bundle item:Start --}}
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 px-2">
                <div class="single-bundle-item">
                    <div class="bundle-image-container">
                        <img src="https://picsum.photos/500?random=2" class="bundle-image" alt="bundle image">

                        <div class="bundle-image-plus-icon">
                            <i class="ti-plus"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-info p-2">
                        <a class="bundle-name" href="#">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perspiciatis, quos.</a>

                        <div class="dropdown bundle-action-dropdown">
                            <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                  </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#"><i class="ti-pencil"></i> Edit</a>
                              <a class="dropdown-item" href="#"><i class="ti-layers"></i> Clone</a>
                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>
                            </div>
                          </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-data p-2">
                        <div class="bundle-price">
                            <span>$52.33</span>
                            <span>Sales</span>
                        </div>

                        <div class="bundle-enrollment-count">
                            <span>9</span>
                            <span>Enrollments</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Single bundle item:End --}}

            {{-- Single bundle item:Start --}}
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 px-2">
                <div class="single-bundle-item">
                    <div class="bundle-image-container">
                        <img src="https://picsum.photos/500?random=3" class="bundle-image" alt="bundle image">

                        <div class="bundle-image-plus-icon">
                            <i class="ti-plus"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-info p-2">
                        <a class="bundle-name" href="#">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perspiciatis, quos.</a>

                        <div class="dropdown bundle-action-dropdown">
                            <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                  </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#"><i class="ti-pencil"></i> Edit</a>
                              <a class="dropdown-item" href="#"><i class="ti-layers"></i> Clone</a>
                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>
                            </div>
                          </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-data p-2">
                        <div class="bundle-price">
                            <span>$52.33</span>
                            <span>Sales</span>
                        </div>

                        <div class="bundle-enrollment-count">
                            <span>9</span>
                            <span>Enrollments</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Single bundle item:End --}}

            {{-- Single bundle item:Start --}}
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 px-2">
                <div class="single-bundle-item">
                    <div class="bundle-image-container">
                        <img src="https://picsum.photos/500?random=4" class="bundle-image" alt="bundle image">

                        <div class="bundle-image-plus-icon">
                            <i class="ti-plus"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-info p-2">
                        <a class="bundle-name" href="#">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perspiciatis, quos.</a>

                        <div class="dropdown bundle-action-dropdown">
                            <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                  </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#"><i class="ti-pencil"></i> Edit</a>
                              <a class="dropdown-item" href="#"><i class="ti-layers"></i> Clone</a>
                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>
                            </div>
                          </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-data p-2">
                        <div class="bundle-price">
                            <span>$52.33</span>
                            <span>Sales</span>
                        </div>

                        <div class="bundle-enrollment-count">
                            <span>9</span>
                            <span>Enrollments</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Single bundle item:End --}}

            {{-- Single bundle item:Start --}}
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 px-2">
                <div class="single-bundle-item">
                    <div class="bundle-image-container">
                        <img src="https://picsum.photos/500?random=5" class="bundle-image" alt="bundle image">

                        <div class="bundle-image-plus-icon">
                            <i class="ti-plus"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-info p-2">
                        <a class="bundle-name" href="#">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perspiciatis, quos.</a>

                        <div class="dropdown bundle-action-dropdown">
                            <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                  </svg>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="#"><i class="ti-pencil"></i> Edit</a>
                              <a class="dropdown-item" href="#"><i class="ti-layers"></i> Clone</a>
                              <a class="dropdown-item" href="#"><i class="ti-trash"></i> Delete</a>
                            </div>
                          </div>
                    </div>

                    <div class="d-flex justify-content-between bundle-data p-2">
                        <div class="bundle-price">
                            <span>$52.33</span>
                            <span>Sales</span>
                        </div>

                        <div class="bundle-enrollment-count">
                            <span>9</span>
                            <span>Enrollments</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Single bundle item:End --}}
        </div>
        {{-- Bundle section:End --}}
    </div>
</section>
@endsection
@push('script')  
    <script>
        
    </script>
@endpush