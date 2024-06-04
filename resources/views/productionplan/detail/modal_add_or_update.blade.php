<div class="modal fade show" id="modalAddOrUpdate" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Create') }} {{ __('Plan') }} {{ __('Production') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="from" method="post" action="{{ route('productionplan.detail.add_or_update') }}">
        @csrf
        <div class="modal-body">
          <div class="row">               
            <div class="form-group col-md-4 d-none">
              <label for="idPlan">{{__('ID')}}</label>
              <input type="text" value="{{$plan->ID}}" class="form-control" id="idPlan" name="ID" readonly>
            </div>
            <div class="form-group col-md-4 d-none">
              <label for="idPlan">{{__('Detail')}}</label>
              <input type="text" value="" class="form-control" id="idDetail" name="idDetail" readonly>
            </div>
            <div class="form-group col-md-4">
              <label for="Product">{{__('Product')}} {{ __('Production') }}</label>
              <select class="custom-select " name="Product" id="Product">
		            <option value="">
		                {{__('Choose')}} {{__('Product')}}
		            </option>
                @foreach($product as $pro)
                  <option value="{{$pro->ID}}">
                        {{$pro->Symbols}}
                  </option>
                @endforeach         		
		          </select>
            </div>
            <div class="form-group col-md-4">
              <label for="Type">{{__('Type')}} {{ __('Production') }}</label>
              <select class="custom-select " name="Type" disabled>
		            <option value="">
		                {{__('Machine')}}
		            </option>
		          </select>
            </div>
            <div class="form-group col-md-4">
              <label for="Machine">{{__('Machine')}} {{ __('Production') }}</label>
              <select class="custom-select " name="Machine" id="Machine">
		            <option value="">
                    {{__('Choose')}}  {{__('Machine')}}
		            </option>   
                @foreach($machine as $mac)
                  <option value="{{$mac->ID}}">
                        {{$mac->Symbols}}
                  </option>
                @endforeach
		          </select>
            </div>
            <div class="form-group col-md-4">
              <label for="Mold_ID">{{__('Mold')}} {{ __('Production') }}</label>
              <select class="custom-select " name="Mold_ID" id="Mold">
		            <option value="">
                    {{__('Choose')}}  {{__('Mold')}}
		            </option>   
                @foreach($mold as $mol)
                  <option value="{{$mol->ID}}">
                        {{$mol->Name}}
                  </option>
                @endforeach
		          </select>
            </div>
            <div class="form-group col-md-4">
              <label for="Quantity">{{__('Quantity')}} {{ __('Production') }}</label>
              <input type="number" value="" class="form-control" id="Quantity" min="0" max ="999999999"  name="Quantity" maxlength="10">
            </div>
            <div class="form-group col-md-4">
              <label for="Date">{{__('Day')}}</label>
              <input type="text" value="" class="form-control date" id="Date" name="Date" >
            </div>
            <div class="form-group col-md-4">
              <label for="Version">{{__('Version')}}</label>
              <input type="text" value="" class="form-control Version" id="Version" name="Version" >
            </div>
            <div class="form-group col-md-4">
              <label for="His">{{__('His')}}</label>
              <input type="text" value="" class="form-control His" id="His" name="His" >
            </div>
            <div class="form-group col-md-4 d-none">
              <label for="Time_Start">{{__('Time Start')}}</label>
              <input type="text" value="" class="form-control datetime" id="Time_Start" name="Time_Start" >
            </div>
            <div class="form-group col-md-4 d-none">
              <label for="Time_End">{{__('Time End')}}</label>
              <input type="text" value="" class="form-control datetime1" id="Time_End" name="Time_End" >
            </div>`
            <div class="form-group col-md-12">
              <label for="symbolsPlan">{{__('Note')}}</label>
              <input type="text" value="{{old('Note') ? old('Note') : '' }}" class="form-control" id="NotePlan" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}">
            </div>
          </div>     
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
          <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
    <script>
        $('.select2').select2()
        $('.datetime').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        })
        $('.datetime1').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour').add(1, 'day'),
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        })
        $('.date').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            locale: {
                format: 'YYYY-MM-DD'
            }
        })
    </script>
@endpush