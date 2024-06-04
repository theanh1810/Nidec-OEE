<div class="modal fade" id="modalEditLayoutPoint" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<span class="modal-title" id="exampleModalLongTitle" style="font-size: 15px; font-weight: bold;">
        			{{__('Edit')}} <span id="idPoint"></span>
        		</span>
    			<button id="btnClose" type="button" class="close" data-dismiss="modal" aria-label="Close">
      				<span aria-hidden="true">&times;</span>
    			</button>
      		</div>
      		<div class="modal-body">
      	  		<div id="loadingEditLayoutPoint" style="display: none">
  	  				<x-my-alert type='success' text='{{__("Update")}}...'/>   	  	
      	  		</div>
	      	</div>
	      	<div class="col-sm-12 row">
	      		<x-my-input 
	      			name="{{__('Name')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="namePoint"
	      			myClass="col-md-6 form-group"
	      			idMess="errNamePoint"
	      			place="{{__('Enter')}} {{__('Name')}}"
	      		/>
				<div class="form-group col-sm-6 row" style="float: left;">
					<span class="col-md-5 my-span">{{__('Layout')}}</span>
                  	<select id="selectLayout" class="form-control form-control-sm col-md-7 select2 select-location select-layout" style="" tabindex="-1" aria-hidden="true">
						<option value="0">{{__('Select')}} {{__('Layout')}}</option>
						@foreach($layouts as $key => $value)
                    		<option value="{{$value->ID}}" {{($value->ID == $sel)? "selected" : ""}}> 
                    			{{$value->Name}} 
                    		</option>
                    	@endforeach
					</select>
					<i class="fas fa-times-circle"  id="errNamePoint" style="display:none; color:red"></i>	
				</div>
				<x-my-input 
	      			name="{{__('Map')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="mapName"
	      			myClass="col-md-3 form-group"
	      			idMess="errMapNamePoint"
	      			place="{{__('Enter')}} {{__('Map')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('Angle')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="angle"
	      			myClass="col-md-3 form-group"
	      			idMess="errAngle"
	      			place="{{__('Enter')}} {{__('Angle')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('Offset')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="offset"
	      			myClass="col-md-3 form-group"
	      			idMess="errOffset"
	      			place="{{__('Enter')}} {{__('Offset')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('Code')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="code"
	      			myClass="col-md-3 form-group"
	      			idMess="errCode"
	      			place="{{__('Enter')}} {{__('Code')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('X')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="xPoint"
	      			myClass="col-md-3 form-group"
	      			idMess="errX"
	      			place="{{__('Enter')}} {{__('X')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('Y')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="yPoint"
	      			myClass="col-md-3 form-group"
	      			idMess="errY"
	      			place="{{__('Enter')}} {{__('Y')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('H')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="hPoint"
	      			myClass="col-md-3 form-group"
	      			idMess="errH"
	      			place="{{__('Enter')}} {{__('H')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('W')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="wPoint"
	      			myClass="col-md-3 form-group"
	      			idMess="errW"
	      			place="{{__('Enter')}} {{__('W')}}"
	      		/>
	      		<x-my-input 
	      			name="{{__('REV')}}" 
	      			col0="col-md-5 my-span" 
	      			col1="col-md-7" 
	      			read="1"
	      			myId="revPoint"
	      			myClass="col-md-3 form-group hide"
	      			idMess="errRev"
	      			place="{{__('Enter')}} {{__('REV')}}"
	      		/>
			</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" id="btnClosePoint"><i class="fas fa-times"></i>
	        		{{__('Close')}}
	        	</button>
	        	<button type="button" class="btn btn-primary btn-sm"  id="saveLayoutPoint"><i class="fas fa-save"></i>
	        		{{__('Save')}}
	        	</button>
	        	<button type="button" class="btn btn-danger btn-sm"  id="delLayoutPoint"><i class="fas fa-trash"></i>
	        		{{__('Delete')}}
	        	</button>
	      	</div>
    	</div>
  	</div>
</div>