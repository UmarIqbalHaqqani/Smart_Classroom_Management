@extends('backEnd.master')
@section('title') 
    @lang('admin.id_card_list')
@endsection
@section('mainContent')
@php
    $breadCrumbs = 
    [
        'h1'=> __('admin.id_card'),
        'bcPages'=> [               
                '<a href="#">'.__('admin.admin_section').'</a>',
                ],
    ];
@endphp
<x-bread-crumb-component :breadCrumbs="$breadCrumbs" />
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                <div class="row">
                <div class="offset-lg-8 col-lg-4 text-right col-md-12 mb-2">
                    @if(userPermission('create-id-card'))
                        <a href="{{route('create-id-card')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                                @lang('admin.create_id_card')
                        </a>
                    @endif
                </div>
            </div>
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-15">@lang('admin.id_card_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('common.sl')</th>
                                            <th>@lang('admin.title')</th>
                                            <th>@lang('admin.role')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($id_cards as $key=>$id_card)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$id_card->title}}</td>
                                                <td>
                                                    @php
                                                        $role_id= ($id_card->role_id == 2) ? 2 : 0;
                                                        $role_names= App\SmStudentIdCard::roleName($id_card->id);
                                                    @endphp
                                                    @foreach ($role_names as $key =>$role_name)
                                                        {{$role_name->name}} {{ ($loop->iteration > 1 && !$loop->last) ? ',' :'' }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                   
                                                    <x-drop-down>
                                                        <a class="dropdown-item preview"  href="{{ route('id-cart-preview',$id_card->id) }}">
                                                            @lang('admin.preview')
                                                        </a>
                                                        @if(userPermission('student-id-card-edit'))
                                                        <a class="dropdown-item" href="{{route('student-id-card-edit',['id' => $id_card->id])}}">@lang('common.edit')</a>
                                                        @endif
                                                        @if(userPermission('student-id-card-delete'))
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteIdCard{{$id_card->id}}" href="#">
                                                            @lang('common.delete')
                                                        </a>
                                                        @endif
                                                    </x-drop-down>
                                                    
                                                </td>
                                            </tr>
    
                                            {{-- Preview Modal Start --}}
                                            
                                            {{-- Preview Modal End --}}
    
                                            {{-- Delete Modal Start --}}
                                            <div class="modal fade admin-query" id="deleteIdCard{{$id_card->id}}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('common.delete_id_card')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                            </div>

                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">
                                                                    @lang('common.cancel')
                                                                </button>
                                                                {{ Form::open(['route' =>'student-id-card-delete', 'method' => 'POST']) }}
                                                                    <input type="hidden" name="id" value="{{$id_card->id}}">
                                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                                {{ Form::close() }}
                                                            </div>
                                                        </div>
    
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- Delete Modal End --}}
                                            @endforeach
                                    </tbody>
                                </table>
                            </x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div id="appendModal">


</div>


@endsection
@include('backEnd.partials.data_table_js')
@push('script')
    <script>
        $(document).ready(function(){
            $(document).on('click','.preview',function(event){
                event.preventDefault();
                let url = $(this).attr('href');
                $.ajax({
                    url:url
                }).done(function(response){
                    if(response.status == 1)
                    {
                        $("#appendModal").html(response.view);
                        $("#previewIdCard").modal('show');
                    }else{
                        toastr.error(response.msg,'Error');
                    }
                });
            });
        });
    </script>
@endpush