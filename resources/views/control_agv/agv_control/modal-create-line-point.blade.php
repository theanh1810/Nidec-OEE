<div class="modal fade" id="modalCreateLinePoint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">{{__('Insert')}}</h5>
	        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button> -->
	      </div>
	      <div class="modal-body">
	      	<div id="loadingInsertLinePoint" style="display: none">
  	  			<x-my-alert type='success' text='{{__("Loading")}}...'/>     	  			
  	  		</div>
	        <div id="requestLinePoint"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="linePointCLose"><i class="fas fa-times"></i>
	        	{{__('Close')}}
	        </button>
	        <button type="button" class="btn btn-primary" id="linePointSave"><i class="fas fa-save"></i>
	        	{{__('Save')}}
	        </button>
	      </div>
	    </div>
	</div>
</div>