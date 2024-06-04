{{-- @extends('layouts.app')

@section('content')

    @if(Auth::user()->checkRole('delete_plan') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('productionplan.destroy')])
	@endif
    @if(Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
	@include('productionplan.command.modal_add_or_update')
	@endif
	<div class="p-3">
		<div class="card">
			<div class="card-header">
				<span class="text-bold" style="font-size: 23px">
					{{ __('Create') }} {{ __('Plan') }} {{ __('Production') }} 
				</span>
				<div class="card-tools">
					@if(Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
					  <button class="btn btn-info btn-create" data-toggle="modal" data-target="#modalAddOrUpdate">
						  {{__('Create')}} {{ __('Plan') }} {{ __('Production') }}
					  </button>
					  @endif
				</div>
			</div>
			<div class="card-body">
				<form action="{{ route('productionplan.filter') }}" method="post">
					@csrf
					<div class="row">
						<div class="form-group col-md-2">
							<label>{{__('Choose')}} {{__('Name')}}</label>
							<select class="custom-select select2" name="Name">
								  <option value="">
									  {{__('Choose')}} {{__('Name')}}
								  </option>
								@foreach($plans as $value)
									  <option value="{{ $value->Name }}">
										  {{ $value->Name }}
									  </option>
								  @endforeach
							</select>
						  </div>
						<div class="form-group col-md-2">
							<label>{{__('Choose')}} {{__('Symbols')}}</label>
							<select class="custom-select select2" name="Symbols">
								  <option value="">
									  {{__('Choose')}} {{__('Symbols')}}
								  </option>
								  @foreach($plans as $value)
									  <option value="{{ $value->Symbols }}">
										  {{ $value->Symbols }}
									  </option>
								  @endforeach
							</select>
						  </div>
						  <div class="form-group col-md-6">
							<label for="MonthPlan">{{__('Month')}}</label>
							<input type="Number" min="1" max="12" step="1" value="" class="form-control" id="MonthPlan" name="Month" placeholder="{{__('Enter')}} {{__('Month')}}" required>
							</div>
							<div class="form-group col-md-6">
							<label for="YearPlan">{{__('Year')}}</label>
							<input type="Number"  min="{{Carbon\Carbon::now()->year}}" step="1" value="" class="form-control" id="MonthPlan" name="Year" placeholder="{{__('Enter')}} {{__('Year')}}" required>
						   </div>
						  <div class="col-md-2" >
							  <button type="submit" class="btn btn-info">{{__('Filter')}}</button>
						  </div>
					  </div>

				</form>
				@include('basic.alert')
				</br>
				<h3  class="text-bold" >
					{{ __('List') }} {{ __('Plan') }} {{ __('Production') }}
				</h3>
				<br>
				<table class="table table-striped table-hover table-bordered" id="tableplan" width="100%" >
					<thead style="background: #D7FAF7;">
						<th>{{__('ID')}}</th>
						<th>{{__('Name')}} {{__('Plan')}}</th>
						<th>{{__('Symbols')}} {{__('Plan')}}</th>
						<th>{{__('Month')}}</th>
						<th>{{__('Year')}}</th>
						<th>{{__('Note')}}</th>
						<th>{{__('User Created')}}</th>
						<th>{{__('Time Created')}}</th>
						<th>{{__('User Updated')}}</th>
						<th>{{__('Time Updated')}}</th>
						<th>{{__('Action')}}</th>
					</thead>
					<tbody>
					@foreach($plan as $value)
							<tr>
								<th colspan="1">{{$value->ID}}</th>
								<td>{{$value->Name}}</td>
								<td>{{$value->Symbols}}</td>
								<td>{{$value->Month}}</td>
								<td>{{$value->Year}}</td>
								<td>{{$value->Note}}</td>
								<td>
									{{$value->user_created ? $value->user_created->name : ''}}
								</td>
								<td>{{$value->Time_Created}}</td>
								<td>
									{{$value->user_updated ? $value->user_updated->name : ''}}
								</td>
								<td>{{$value->Time_Updated}}</td>
								<td>
									<a href="{{ route('productionplan.detail', ['ID' => $value->ID])}}" class="btn btn-warning">
										{{__('Detail')}}
									</a>
									@if(false)
									<a href="{{ route('productionplan.detail', ['ID' => $value->ID])}}" class="btn btn-success">
										{{__('Edit')}}
									</a>
									@endif
									@if(Auth::user()->checkRole('delete_plan') || Auth::user()->level == 9999)
									<button id="del-{{$value->ID}}" class="btn btn-danger btn-delete">
										{{__('Delete')}}
									</button>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
@push('scripts')
	<script>
		$('.select2').select2()

		$('#tableplan').DataTable({
			language: __languages.table,
			scrollX : '100%',
			scrollY : '100%'
		});

		$('.btn-create').on('click', function()
		{
			$('#namePlan').val('')
		});

		$(document).on('click', '.btn-delete', function()
            let id = $(this).attr('id');
            let name = $(this).parent().parent().children('td').first().text();
            $('#modalRequestDel').modal();
            $('#nameDel').text(name);
            $('#idDel').val(id.split('-')[1]);
        });
	</script>
@endpush --}}


@extends('layouts.app')

@section('content')
	@if(Auth::user()->checkRole('delete_plan') || Auth::user()->level == 9999)
		@include('basic.modal_request_destroy', ['route' => route('productionplan.destroy')])
	@endif
    @if(Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
		@include('productionplan.command.modal_add_or_update')
	@endif
	<div class="card">
		<div class="card-header">
			<span class="text-bold" style="font-size: 23px">
					{{ __('Create') }} {{ __('Plan') }} {{ __('Production') }}
			</span>
			<div class="card-tools">
				@if(Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
					<button class="btn btn-info btn-create" data-toggle="modal" data-target="#modalAddOrUpdate">
						{{__('Create')}} {{ __('Plan') }} {{ __('Production') }}
					</button>
				@endif
			</div>
		</div>
		<div class="card-bordy p-3">
				<div class="h-space">
					<div class="form-group w-20">
						<label>{{__('Symbols')}} </label>
						<select class="custom-select select2 symbols" name="Symbols">
							<option value="">
								{{__('Choose')}} {{__('Symbols')}}
							</option>
							@foreach($plans as $value)
								<option value="{{ $value->Symbols }}">
									{{ $value->Symbols }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="form-group w-20">
						<label>{{__('Name')}}</label>
						<select class="custom-select select2 name" name="Name">
							<option value="">
								{{__('Choose')}} {{__('Name')}}
							</option>
							@foreach($plans as $value)
								<option value="{{ $value->Name }}">
									{{ $value->Name }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="form-group w-20">
						<label for="MonthPlan">{{__('Month')}}</label>
						<input type="Number" min="1" max="12" step="1" value="" class="form-control" id="Month" name="Month" placeholder="{{__('Enter')}} {{__('Month')}}" required>
					</div>
					<div class="form-group w-20">
						<label for="YearPlan">{{__('Year')}}</label>
						<input type="Number"   step="1" value="" class="form-control" id="Year" name="Year" placeholder="{{__('Enter')}} {{__('Year')}}" required>
					</div>
					<button type="submit" class="btn btn-info w-10 filter">{{__('Filter')}}</button>
				</div>
				@include('basic.alert')
			<table class="table table-bordered table-striped text-nowrap w-100"></table>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/production-plan/command.js') }}"></script>
@endpush