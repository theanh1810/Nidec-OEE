<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 75%">
	  <div class="modal-content">
	    <div class="modal-header">
	      <span>
	      	{{__('Detail')}} <span id="agvNameDetail"></span>
	      </span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
	    </div>
	    <div class="modal-body">
      	<div class="row">
      		<!-- Col 1 -->
      		<x-my-input 
      			name="{{__('Position')}} {{__('Now')}}" 
      			col0="col-md-4" 
      			col1="col-md-8"
      			myClass="col-md-4"
      			myId="positionNow" 
      			read="0"
      		/>
      		<x-my-input 
      			name="{{__('X')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="xAgv" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Command')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="commandNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<!-- col 2 -->
      		<x-my-input 
      			name="{{__('Position')}} {{__('Prext')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="positionPrext" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Y')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="yAgv" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Process')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="processNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<!-- col 3 -->
      		<x-my-input 
      			name="{{__('Battery')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="batteryNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Angle')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="angleNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Destination')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="destinationNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<!-- col 4 -->
      		<x-my-input 
      			name="{{__('Code')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="codeNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Direction')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="directionNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Task')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="taskNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<!-- col 5 -->
      		<x-my-input 
      			name="{{__('Code')}} {{__('Prext')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="codePrext" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Differrence')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="differrenceNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Regime')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="regimeNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<!-- col 6 -->
      		<x-my-input 
      			name="{{__('Time')}} {{__('Ping')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="timePing" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Action')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="actionNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Status')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="statusNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<!-- col 7 -->
      		<x-my-input 
      			name="{{__('Route')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="routeNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Avoid')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="avoidNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      		<x-my-input 
      			name="{{__('Distance')}}" 
      			col0="col-md-4" 
      			col1="col-md-8" 
      			myId="distanceNow" 
      			read="0"
      			myClass="col-md-4"
      		/>
      	</div>
	    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" id="closeDetail">
        	<i class="fas fa-times"></i> 
        	{{__('Close')}}
        </button>
      </div>
	  </div>
	</div>
</div>