@extends('layouts.app')

@section('content')
	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.unit.destroy')])
	@endif
	@include('basic.modal_table_history')
	<div class="card">
		<div class="card-header">
			<span class="text-bold" style="font-size: 23px">
				{{ __('Unit') }}
			</span>
		</div>
		<div class="card-bordy p-3">
				<div class="h-space">
					<div class="form-group w-20">
						<label>{{__('Symbols')}} {{ __('Unit') }}</label>
						<select class="custom-select select2 symbols" name="Symbols">
							<option value="">
								{{__('Choose')}} {{__('Symbols')}}
							</option>
							@foreach($units as $value)
								<option value="{{ $value->Symbols }}" {{$request ? ($request->Symbols == $value->Symbols ? 'selected' : '') : ''}}>
									{{ $value->Symbols }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="form-group w-20">
						<label>{{__('Name')}} {{ __('Unit') }}</label>
						<select class="custom-select select2 name" name="Name">
							<option value="">
								{{__('Choose')}} {{__('Name')}}
							</option>
							@foreach($units as $value)
								<option value="{{ $value->Name }}" {{$request ? ($request->Name == $value->Name ? 'selected' : '') : ''}}>
									{{ $value->Name }}
								</option>
							@endforeach
						</select>
					</div>
					<button type="submit" class="btn btn-info w-10 filter">{{__('Filter')}}</button>
					@if(Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
						<a href="{{ route('masterData.unit.show') }}" class="btn btn-success w-10">
							{{__('Create')}}
						</a>
						<button id="history-` + data + `" class="btn btn-warning btn-history" style="width: 80px">
							{{__('History')}}
						</button>
					@endif
				</div>
			<table class="table table-unit table-bordered table-striped text-nowrap w-100"></table>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('js/master-data/unit.js') }}"></script>
@endpush