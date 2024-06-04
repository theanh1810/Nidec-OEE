<div class="modal fade" id="modalRequestDelPoint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">{{__('Delete')}}</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<div id="loadingDeletePoint" style="display: none">
  	  			<x-my-alert type='danger' text='{{__("Loading")}}...'/>	
  	  		</div>
	        <h2>{{__('Do You Want To Delete')}} <span style="color: blue" id="point"></span> ???</h2>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
	        	{{__('Close')}}
	        </button>
	        <button type="button" class="btn btn-danger" id="deletePoint"><i class="fas fa-save"></i>
	        	{{__('Delete')}}
	        </button>
	      </div>
	    </div>
	</div>
</div>