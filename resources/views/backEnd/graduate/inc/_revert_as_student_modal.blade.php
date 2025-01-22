<div class="modal-body">
    @if(isset($graduate))
        {{ Form::open(['class' => 'form-horizontal','route' => 'alumni.revert-as-student', 'method' => 'POST']) }}
        <input type="hidden" name="id" value="{{$graduate->id}}">
     
        <div class="row">
              <div class="col-lg-12">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="primary_input mt-2 pt-1 mb-20 text-center">
                                <i class="fa fa-warning fa-2x text-danger"></i>
                                <p class="text-danger">Are you sure you want to revert this graduated student back to their status as a regular student ?</p>
                            </div>
                      </div>  
                </div>
          </div>

          <div class="row mx-auto">
            <div class="col-lg-12 text-center">
                <button type="submit" class="primary-btn fix-gr-bg text-nowrap" data-toggle="tooltip">
                    <span class="ti-check"></span>
                    @lang('common.update')
                </button>
            </div>
        </div> 
      {{ Form::close() }}
    </div>
    @endif 