@extends('layouts.app')

@section('content')
	@if(Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
	@include('basic.modal_request_destroy', ['route' => route('masterData.product.destroy')])
	@endif
	@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
	@include('basic.modal_import', ['route' => route('masterData.product.import_file')])
	@endif
	@include('basic.modal_table_history')
	<div class="card">
		<div class="card-header">
			<span class="text-bold" style="font-size: 23px">
				{{ __('B.O.M') }}
			</span>
			<div class="card-tools">
				
			</div>
		</div>
		<div class="card-bordy p-3">
				<div class="h-space">
					<div class="form-group w-20">
						<label>{{__('Symbols')}} {{ __('Product') }}</label>
						<select class="custom-select select2 symbols" name="Symbols">
							<option value="">
								{{__('Choose')}} {{__('Symbols')}}
							</option>
							@foreach($product as $value)
								<option value="{{ $value->Symbols }}" >
									{{ $value->Symbols }}
								</option>
							@endforeach
						</select>
					</div>
					<button type="submit" class="btn btn-info w-10 filter">{{__('Filter')}}</button>
					@if(Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
					<a href="{{ route('masterData.product.bom') }}" class="btn btn-success w-10">
						{{__('Create')}}
					</a>
					@endif
					@if(Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
					<button class="btn btn-success btn-import" style="width: 180px">
						{{__('Import By File Excel')}}
					</button>
					@endif
					<button type="button"  class="btn btn-warning btn-history" style="width: 80px">
						{{__('History')}}
					</button>
					<button type="button"  class="btn btn-warning btn-export-excel" style="width: 100px">
						{{__('Export')}} {{__('Excel')}}
					</button>
				</div>
				@include('basic.alert')
			<table class="table table-product table-bordered table-striped text-nowrap w-100" style="vertical-align: middle !important"></table>
		</div>
	</div>
@endsection
@push('scripts')
    <script src="{{ asset('js/master-data/product.js') }}"></script>
	<script>
        $('.btn-export-excel').on('click', function(e) {
            e.preventDefault();
            var fill_symbols = ($('.symbols').val() == undefined) ? '' : $('.symbols').val();
            var url = "{{route('masterData.product.export_file', ['template' => 0])}}";
            url = url + '&symbols=' + fill_symbols;
            window.location.href = url;
        });

    </script>
@endpush
