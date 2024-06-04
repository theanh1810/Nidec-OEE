<div class="modal fade" id="modalAGV" tabindex="-1" role="dialog" aria-labelledby="modalAGVLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAGVLabel">{{__('Choose')}} {{__('AGV')}}</h5>
      </div>
      <div class="modal-body">
        <div class="row">
        @foreach($agvs as $agv)
          <div class="custom-control custom-checkbox col-md-4">
            <input class="custom-control-input check-box" type="checkbox" id="agv-{{$agv->ID}}" value="{{$agv->ID}}" checked="">
            <label for="agv-{{$agv->ID}}" class="custom-control-label">{{ $agv->Name }}</label>
          </div>
        @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          {{__('Close')}}
        </button>
        <button type="button" class="btn btn-primary btn-agv">
          {{__('Choose')}}
        </button>
      </div>
    </div>
  </div>
</div>