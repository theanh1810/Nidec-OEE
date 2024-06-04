<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="modalNoteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNoteLabel">{{__('Note')}}</h5>
      </div>
      <div class="modal-body">
        <div class="">
            {{__('Run')}}  : X <br>
            {{__('Stop')}} : Y <br>
            {{__('Error')}}: Z <br>
            {{__('Total')}}: T = X + Y + Z <br>
            {{__('Efficiency')}} {{__('Run')}}   = (X/T)*100 (%) <br>
            {{__('Efficiency')}} {{__('Stop')}}  = (X/T)*100 (%) <br>
            {{__('Efficiency')}} {{__('Error')}} = (X/T)*100 (%) <br>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          {{__('Close')}}
        </button>
      </div>
    </div>
  </div>
</div>