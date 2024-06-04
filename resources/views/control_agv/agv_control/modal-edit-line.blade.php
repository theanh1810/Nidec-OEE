<div class="modal fade" id="modalEditLine" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLongTitle">{{__('Edit :name', ['name' => 'line'])}}</h5>
    			<button id="btnClose" type="button" class="close" data-dismiss="modal" aria-label="Close">
      				<span aria-hidden="true">&times;</span>
    			</button>
      		</div>
      		<div class="modal-body">
      	  		<div id="loadingEditLine" style="display: none">
  	  				<x-my-alert type='success' text='{{__("Update")}}...'/>	      	  			
      	  		</div>
		      	<div class="alert alert-danger alert-dismissible" id="alertErrorEdit" style="display: none">
	                <h5><i class="icon fas fa-ban"></i> {{__('Error')}}!</h5>
	                <div id="nameErrorEdit"></div>
			    </div>

			    <div class="col-sm-5" style="float: left; border: 1px solid black;" id="N1">
			    	<img src="{{ asset('dist/img/len.png') }}" class="center">
			    	<div id="nameN1" style="display: none"></div>
			    </div>
			    
			    <div class="col-sm-5" style="float: left; border: 1px solid black; height: 64px" id="N2">
			    	<img src="{{ asset('dist/img/trai.png') }}" class="center1">
			    	<div id="nameN2" style="display: none"></div>
			    </div>

				<div class="col-sm-2" style="float: left;"> {{__(' ')}}</div>

				<div class="col-sm-5" style="float: right; border: 1px solid black;" id="N3">
			    	<img src="{{ asset('dist/img/xuong.png') }}" class="center">
			    	<div id="nameN3" style="display: none"></div>
			    </div>

			    <div class="col-sm-5" style="float: right; border: 1px solid black; height: 64px;" id="N4">
			    	<img src="{{ asset('dist/img/phai.png') }}" class="center1">
			    	<div id="nameN4" style="display: none"></div>
			    </div>

			    <div class="col-sm-12" style="float: right; border: 1px solid black; height: 150px;" id="N13">
			    	<img src="{{ asset('dist/img/1-3.png') }}" class="center0">
			    	<div id="nameN13" style="display: none"></div>
			    </div>

			    <div class="col-sm-12" style="float: right; border: 1px solid black; height: 150px;" id="N24">
			    	<img src="{{ asset('dist/img/2-4.png') }}" class="center2">
			    	<div id="nameN24" style="display: none"></div>
			    </div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeEdit"><i class="fas fa-times"></i> {{__('Close')}}</button>
	        	<button type="button" class="btn btn-primary"  id="saveLine"><i class="fas fa-save"></i> {{__('Save')}}</button>
	      	</div>
    	</div>
  	</div>
</div> 