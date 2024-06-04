<div class="modal fade show" id="modalAddOrUpdate" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Export') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="from" method="post" action="{{ route('warehouse_system.export_materials.export') }}">
        @csrf
        <div class="modal-body p-3">
          <div class="row">               
            <div class="form-group d-none">
              <label for="idPlan">{{__('ID')}}</label>
              <input type="text" value="" class="form-control" id="idex" name="ID" readonly>
            </div>
            <div class="form-group col-4">
              <label for="Machine">{{__('Machine')}}</label>
              <input type="text" value="" class="form-control" id="Machine" name="Machine" readonly>
            </div>
            <div class="form-group col-4">
              <label for="Materials">{{__('Product')}}</label>
              <input type="text" value="" class="form-control" id="Materials" name="Materials" readonly>
            </div>
            <div class="form-group col-4">
              <label for="namePlan">{{__('Type')}} {{ __('Transport') }}</label>
              <select class="custom-select" name="Type">
		            <option value="0">
		                {{__('Choose')}} {{__('Type')}}
		            </option>
                <option value="1">
		                {{__('AGV')}}
		            </option>
                <option value="2">
		                {{__('Normal')}}
		            </option>		
		          </select>
            </div>
            <div class="form-group col-4">
              <label for="Quantity">{{__('Quantity')}}</label>
              <input type="Number" value="" step="0.0001" class="form-control" id="Quantity" name="Quantity" min="0" required>
            </div>
            <div class="form-group col-4">
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
    </script>
@endpush