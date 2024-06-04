<div class="modal fade show" id="modalAddOrUpdate" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Create') }} {{ __('Plan') }} {{ __('Production') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="from" method="post" action="{{ route('productionplan.addOrUpdate') }}">
        @csrf
        <div class="modal-body">
          <div class="row">               
            <div class="form-group col-md-4 d-none">
              <label for="idPlan">{{__('ID')}}</label>
              <input type="text" value="{{old('ID') ? old('ID') : 0 }}" class="form-control" id="idPlan" name="ID" readonly>
            </div>
            <div class="form-group col-md-12">
              <label for="namePlan">{{__('Name')}} {{ __('Plan') }} {{ __('Production') }}</label>
              <input type="text" value="{{old('Name')? old('Name') : '' }}" class="form-control" id="namePlan" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" maxlength="50">
            </div>
         
            <div class="form-group col-md-6">
              <label for="MonthPlan">{{__('Month')}}</label>
              <input type="Number" min="1" max="12" step="1" value="{{old('Month') ? old('Month') : '' }}" class="form-control" id="MonthPlan" name="Month" placeholder="{{__('Enter')}} {{__('Month')}}" required>
              @if($errors->any())
                <span role="alert">
                  <strong style="color: red">{{$errors->first('Month')}}</strong>
                </span>
              @endif
            </div>
            <div class="form-group col-md-6">
              <label for="YearPlan">{{__('Year')}}</label>
              <input type="Number"  min="{{Carbon\Carbon::now()->year}}" step="1" value="{{old('Year') ? old('Year') : '' }}" class="form-control" id="MonthPlan" name="Year" placeholder="{{__('Enter')}} {{__('Year')}}" required>
              @if($errors->any())
                <span role="alert">
                  <strong style="color: red">{{$errors->first('Year')}}</strong>
                </span>
              @endif
            </div>
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
    let show = "{{ $errors->any() }}"
    if(show == '')
    {

    } else
    {
      $('#modalAddOrUpdate').modal();
    }
  </script>
@endpush