<div class="modal fade" id="modalDelTran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{__('Do You Want To')}} {{__('Destroy')}} {{__('Command')}} ???
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
          {{__('Close')}}
        </button>
        <button type="button" class="btn btn-danger" id="destroyTranSave"><i class="fas fa-trash-alt"></i>
          {{__('Destroy')}}
        </button>
      </div>
    </div>
  </div>
</div>