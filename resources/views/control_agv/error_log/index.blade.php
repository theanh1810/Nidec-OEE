@extends('layouts.main')

@section('content')
	@include('control_agv.modal.modal_destroy_command')
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-12">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{__('Error')}} {{__('AGV')}}
	                	</span>
	                	<div class="card-tools">
	                        <a class="btn btn-info" href="{{ 
	                        	route('controlAGV.transportSystem.exportListErrorAGV', [
									'AGV'      => isset($_GET['AGV']) ? $_GET['AGV'] : '',
									'Position' => isset($_GET['Position']) ? $_GET['Position'] : '',
									'From'     => isset($_GET['From']) ? $_GET['From'] : '',
									'To'       => isset($_GET['To']) ? $_GET['To'] : '',
	                        	]) 
	                    	}}">
	                        	{{__('Export File Excel')}}
	                        </a>
	                	</div>
	                </div>
	                <div class="card-body">

	                	<form action="{{ route('controlAGV.transportSystem.listErrorAGV') }}" method="get">
	                		@csrf
	                		<div class="row">
		                		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{ __('AGV') }}</label>
		                        	<select class="custom-select select2" name="AGV">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('AGV')}}
		                          		</option>
		                          		@foreach($agvs as $agv)
		                          			<option value="{{ $agv->ID }}"  {{ isset($_GET['AGV']) ? ($_GET['AGV'] == $agv->ID ? 'selected' : '') : ''}}>{{ $agv->Name }}</option>
		                          		@endforeach
		                        	</select>
	                      		</div>

	                      		{{--<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{ __('Position') }}</label>
		                        	<select class="custom-select select2" name="Position">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Position')}}
		                          		</option>
		                          		@foreach($points as $point)
		                          			<option value="{{ $point->ID }}" {{ isset($_GET['Position']) ? ($_GET['Position'] == $point->ID ? 'selected' : '') : ''}}>{{ $point->Name }}</option>
		                          		@endforeach
		                        	</select>
	                      		</div>
	                      		
	                      		<div class="form-group col-md-2">
		                        	<label>{{__('Choose')}} {{ __('Status') }}</label>
		                        	<select class="custom-select select2" name="Status">
		                          		<option value="">
		                          			{{__('Choose')}} {{__('Status')}}
		                          		</option>
		                          		<option value="2" {{ isset($_GET['Status']) ? ($_GET['Status'] == '2' ? 'selected' : '') : '' }}>{{__('Success')}}</option>
		                          		<option value="5" {{ isset($_GET['Status']) ? ($_GET['Status'] == '5' ? 'selected' : '') : '' }}>{{__('Destroy')}}</option>
		                        	</select>
	                      		</div> 
	                      		--}}

	                      		<div class="form-group col-md-2">
	                                <label>{{__('From')}}</label>
	                                <input type="text" class="form-control" id="from" name="From" value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}">
	                                <span role="alert" class="hide from-to">
	                                    <strong style="color: red">
	                                      {{__('Choose')}} {{__('Time')}} {{__('From')}} {{__('And')}} {{__('To')}}
	                                    </strong>
	                                </span>
	                            </div>

	                            <div class="form-group col-md-2">
	                                <label>{{__('To')}}</label>
	                                <input type="text" class="form-control" id="to" name="To" value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}">
	                                <span role="alert" class="hide from-to">
	                                    <strong style="color: red">
	                                      {{__('Choose')}} {{__('Time')}} {{__('From')}} {{__('And')}} {{__('To')}}
	                                    </strong>
	                                </span>
	                            </div>

	                            <div class="form-group col-md-2">
	                                
	                            </div>

	                      		<div class="col-md-12" style="margin-bottom: 33px">
	                      			<button type="submit" class="btn btn-info">{{__('Filter')}}</button>
			                        <a href="{{ route('controlAGV.transportSystem.listErrorAGV') }}" class="btn btn-warning" style="width: 100px">
	                                    {{__('Reset')}}
	                                </a>
	                      		</div>
                      		</div>

	                	</form>
		                @include('basic.alert')
		                <table class="table table-striped table-hover" id="tableUnit" width="100%">
		                	<thead>
		                		<th>{{__('ID')}}</th>
		                		<th>{{__('AGV')}}</th>
						      	<th>{{__('Error Code')}}</th>
						      	<th>{{__('Mode')}}</th>
						      	<th>ID {{__('Command')}}</th>
						      	<th>{{__('Position')}}</th>
						      	<th>{{__('Time')}}(s)</th>
						      	<th>{{__('Time Created')}}</th>
						      	<th>{{__('Time Updated')}}</th>
		                	</thead>
		                	<tbody id="my-tbody">
		                		@foreach($data as $value)
					        		<tr>
					        			<th colspan="1">{{$value->ID}}</th>
					        			<td>
					        				{{$value->agv ? $value->agv->Name : ''}}
					        			</td>
					        			<td>
					        				{{$value->error ? $value->error->ERROR : ''}}
					        			</td>
					        			<td>
					        				{{$value->MODE}}
					        			</td>
					        			<td>
					        				{{$value->TRANS_ID}}
					        			</td>
					        			<td>
					        				{{$value->point ? $value->point->Name : $value->POSITION }}
					        			</td>
					        			<td>{{$value->PERIOD}}</td>
					        			<td>{{$value->CREATED_TIME}}</td>
					        			<td>{{$value->UPDATETIME}}</td>
					        		</tr>
					    		@endforeach
		                	</tbody>
		                </table>
	                </div>
	                <div class="card-footer">
	                	<div class="float-right">
	                		{{ $data->withQueryString()->links() }}
	                	</div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@push('scripts')
	<script>
		$('.select2').select2();

		let from = $('#from').val();
		let to   = $('#to').val();

		if (from == '') 
		{
		    from = moment().startOf('month').format('MM/DD/YYYY');
		}

		if (to == '') 
		{
		    to = moment().format('MM/DD/YYYY');
		}

		$('#from').daterangepicker({
		    timePicker         : false,
		    singleDatePicker   : true,
		    showDropdowns      : true,
		    applyButtonClasses : 'btn-primary',
		    cancelButtonClasses: 'btn-secondary',
		    startDate          : moment(from, 'MM/DD/YYYY').format('MM/DD/YYYY').toString(),
		    locale             : {
		      	// format     : 'DD/MM/YYYY',
		      	cancelLabel: __calculate.cancel,
		      	applyLabel : __calculate.apply
		    }
		});

	  	$('#to').daterangepicker({
		    timePicker         : false,
		    singleDatePicker   : true,
		    showDropdowns      : true,
		    applyButtonClasses : 'btn-primary',
		    cancelButtonClasses: 'btn-secondary',
		    startDate          : moment(to, 'MM/DD/YYYY').format('MM/DD/YYYY').toString(),
		    locale             : {
		      	// format     : 'DD/MM/YYYY',
		      	cancelLabel: __calculate.cancel,
		      	applyLabel : __calculate.apply
		    }
	 	});
	</script>
@endpush