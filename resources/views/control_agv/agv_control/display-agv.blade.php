<div class="card direct-chat direct-chat-primary" id="display" style="padding: 0 !important">
	<div class="card-header text-center" style="font-weight: normal; font-size: 18px">
        <span class="">{{__('Display')}} {{__('AGV')}}</span>
	</div>
	<div class="card-body" style=" font-size: 13px !important">
      	@foreach($data as $key => $value)
      		<div class="col-sm-12" style="margin-top: {{ $key == 0 ? '5px' : '0px' }}; padding: 0;">
	      		<div class="card display-agv" style="border: 2px solid #{{$value->Color}}; display:none ; margin-bottom: 5px;" id="card-display{{$value->AGV_ID}}">
	      			<div class="card-header" style="padding: 0 0 0 5px">
	      				<div class="row">
		      				<div class="col-sm-6">
			                	<span id='textAgv{{$value->AGV_ID}}' class="my-span" style="color: #{{$value->Color}}; font-weight: bold;">
			                		{{$value->NAME}}
			                	</span>
			                	<span id="text-type{{$value->AGV_ID}}" style="display: none">{{$value->Type}}</span>
		                	</div>
		      				<div class="row col-sm-6" style="margin: auto;"> 
		      					<!-- card-tools -->
		      					<div class="bat-big" style="display: flex;">
									<div class="bat-ratio bat-ratio-{{$value->AGV_ID}}">
									</div>
									<span id="bat-{{$value->AGV_ID}}" class="my-span bat-text" style="font-size: 13px">
										10%
									</span>
								</div>
			            		<div class="bat-small"></div>
		      				</div>
	      				</div>
	      			</div>
	      			<div class="card-body" style="margin-top: 5px;">
	      				<x-my-input 
			      			name="{{__('Position')}}" 
			      			col0="col-sm-5 my-span" 
			      			col1="col-md-7 position-{{$value->AGV_ID}}" 
			      			read="0"
			      			myClass="col-md-12"
			      		/>
			      		<x-my-input 
			      			name="{{__('Command')}}" 
			      			col0="col-sm-5 my-span" 
			      			col1="col-md-7 command-{{$value->AGV_ID}}" 
			      			read="0"
			      			myClass="col-md-12"
			      		/>
			      		<x-my-input 
			      			name="{{__('Status')}}" 
			      			col0="col-sm-5 my-span" 
			      			col1="col-md-7 status-{{$value->AGV_ID}}" 
			      			read="0"
			      			myClass="col-md-12"
			      		/>
	      			</div>
	      			<div class="card-footer" style="padding: 3px 0 3px 0">
	      				<div class="text-center">
      					<button class="btn btn-info btn-sm btn-fill my-span" id="btn-fil-{{$value->AGV_ID}}" style="width: 85px;">
      						{{__('Filter')}}
      					</button>
      					<button class="btn btn-warning btn-sm btn-detail my-span" id="btn-detail-{{$value->AGV_ID}}" style="width: 85px;">
      						{{__('Detail')}}
      					</button>
      					</div>
	      			</div>
	      		</div>
      		</div>
		@endforeach
	</div>
</div>