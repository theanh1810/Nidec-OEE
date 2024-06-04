<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<span class="modal-title" id="exampleModalLongTitle" style="font-size: 15px; font-weight: bold;">
        			{{__('Edit')}} {{__('Point')}}
        		</span>
    			<button id="btnClose" type="button" class="close" data-dismiss="modal" aria-label="Close">
      				<span aria-hidden="true">&times;</span>
    			</button>
      		</div>
      		<div class="modal-body">
      	  		<div id="loadingEditPoint" style="display: none">
  	  				<x-my-alert type='success' text='{{__("Update")}}...'/>      	  			
      	  		</div>
      	  		<div id="positionPoint" style="display: none;"></div>
		      	<div class="alert alert-danger alert-dismissible" id="alertErrorEdit" style="display: none">
	                <h5><i class="icon fas fa-ban"></i> {{__('Error')}}!</h5>
	                <div id="nameErrorEdit"></div>
			    </div>
			    <div class="col-md-12 row">
			    	<x-my-input 
		      			name="{{__('Name')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="0"
		      			myId="rectName"
		      			myClass="col-md-12 form-group"
		      			idMess="idLine"
		      			place="{{__('Enter')}} {{__('Name')}}"
		      		/>
				</div>
				<div class="col-md-12 row">
					<x-my-input 
		      			name="{{__('Code')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="editLine"
		      			myClass="col-md-6 form-group"
		      			idMess="errEditCode"
		      			place="{{__('Enter')}} {{__('Code')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('Layout')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="editLayout"
		      			myClass="col-md-6 form-group"
		      			idMess="errEditLayout"
		      			place="{{__('Enter')}} {{__('Layout')}}"
		      		/>
				</div>
				<div class="col-md-12 row">
					<x-my-input 
		      			name="{{__('N1')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="N1Rect"
		      			myClass="col-md-3 form-group"
		      			idMess="errEditN1"
		      			place="{{__('Enter')}} {{__('N1')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('N2')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="N2Rect"
		      			myClass="col-md-3 form-group"
		      			idMess="errEditN2"
		      			place="{{__('Enter')}} {{__('N2')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('N3')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="N3Rect"
		      			myClass="col-md-3 form-group"
		      			idMess="errEditN3"
		      			place="{{__('Enter')}} {{__('N3')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('N4')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="N4Rect"
		      			myClass="col-md-3 form-group"
		      			idMess="errEditN4"
		      			place="{{__('Enter')}} {{__('N4')}}"
		      		/>
					
				</div>
				<div class="col-md-12 row">
					<x-my-input 
		      			name="{{__('X')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="xRect"
		      			myClass="col-md-4 form-group"
		      			idMess="errEditX"
		      			place="{{__('Enter')}} {{__('X')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('Y')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="yRect"
		      			myClass="col-md-4 form-group"
		      			idMess="errEditY"
		      			place="{{__('Enter')}} {{__('Y')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('Z')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="zRect"
		      			myClass="col-md-4 form-group"
		      			idMess="errEditZ"
		      			place="{{__('Enter')}} {{__('Z')}}"
		      		/>
				</div>
				<div class="col-md-12 row">
					<x-my-input 
		      			name="{{__('X Lida')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="editXLida"
		      			myClass="col-md-6 form-group"
		      			idMess="errEditXLida"
		      			place="{{__('Enter')}} {{__('X Lida')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('Y Lida')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="editYLida"
		      			myClass="col-md-6 form-group"
		      			idMess="errEditYLida"
		      			place="{{__('Enter')}} {{__('Y Lida')}}"
		      		/>
				</div>
				<div class="col-md-12 row">
					<x-my-input 
		      			name="{{__('X')}} {{__('Navigation')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="editXNav"
		      			myClass="col-md-6 form-group"
		      			idMess="errEditXNav"
		      			place="{{__('Enter')}} {{__('X')}} {{__('Navigation')}}"
		      		/>
		      		<x-my-input 
		      			name="{{__('Y')}} {{__('Navigation')}}" 
		      			col0="col-md-5 my-span" 
		      			col1="col-md-7" 
		      			read="1"
		      			myId="editYNav"
		      			myClass="col-md-6 form-group"
		      			idMess="errEditYNav"
		      			place="{{__('Enter')}} {{__('Y')}} {{__('Navigation')}}"
		      		/>
				</div>
				
				<p style="color:red; display:none; " id="errEditAll"><i class="fas fa-times-circle"></i> 
					{{__('Fill All Property Of This Point')}}
				</p>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" id="closeEdit"><i class="fas fa-times"></i>
	        		{{__('Close')}}
	        	</button>
	        	<button type="button" class="btn btn-primary btn-sm" id="saveEdit"><i class="fas fa-save"></i>
	        		{{__('Save')}}
	        	</button>
	        	<button type="button" class="btn btn-danger btn-sm" id="deleteEdit"><i class="fas fa-trash-alt"></i>
	        		{{__('Delete')}}
	        	</button>
	      	</div>
    	</div>
  	</div>
</div>