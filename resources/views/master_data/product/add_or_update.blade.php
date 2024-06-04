@extends('layouts.app')

@section('content')
	<div class="card">
		<div class="card-header">
			<span class="text-bold" style="font-size: 23px">
				{{ __('Product') }}
			</span>
		</div>
		<form role="from" method="post" action="{{ route('masterData.product.addOrUpdate') }}">
			@csrf
			<div class="card-body">
				<div class="row">               
					<div class="form-group col-md-4 d-none">
						<label for="idProduct">{{__('ID')}}</label>
						<input type="text" value="{{old('ID') ? old('ID') : ($product ? $product->ID : '') }}" class="form-control" id="idProduct" name="ID" readonly>
					</div>
					<div class="form-group col-md-4">
						<label for="nameProduct">{{__('Name')}} {{ __('Product') }}</label>
						<input type="text" value="{{old('Name')? old('Name') : ($product ? $product->Name : '') }}" class="form-control" id="nameProduct" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" @if($show == null){ readonly }@endif required>
						@if($errors->any())
							<span role="alert">
								<strong style="color: red">{{$errors->first('Name')}}</strong>
							</span>
						@endif
					</div>
					<div class="form-group col-md-4">
						<label for="symbolsProduct">{{__('Symbols')}} {{ __('Product') }}</label>
						<input type="text" value="{{old('Symbols') ? old('Symbols') : ($product ? $product->Symbols : '') }}" class="form-control" id="symbolsProduct" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" @if($show == null){ readonly }@endif required>
						@if($errors->any())
							<span role="alert">
								<strong style="color: red">{{$errors->first('Symbols')}}</strong>
							</span>
						@endif
					</div>
					<div class="form-group col-md-4">
						<label for="unitProduct">{{__('Unit')}}</label>
				            <select name="Unit_ID" class="form-control select2" required>
				                <option value="">
				                    {{__('Choose')}} {{__('Unit')}}
				                </option>
				                @foreach($units as $value)
				                <option value="{{$value->ID}}" {{old('Unit_ID') ? 
				                    (old('Unit_ID') == $value->ID ? 'selected' : '') :
				                    ($product ? ($product->Unit_ID == $value->ID ? 'selected' : '') : '')}}>
				                    {{$value->Symbols}}
				                </option>
				                @endforeach
				            </select>
				            @if($errors->any())
				                <span role="alert">
	                                <strong style="color: red">{{$errors->first('Address')}}</strong>
	                            </span>
				            @endif
				        </div>
				        <div class="form-group col-md-4"> 
				            <label for="Cycle_Time">{{__('Cycle Time')}} ( s )</label>
				            <input type="number" step="0.01" value="{{old('Cycle_Time') ? old('Cycle_Time') : ($product ? $product->Cycle_Time : '') }}" class="form-control" id="Cycle_Time" name="Cycle_Time" placeholder="{{__('Enter')}} {{__('Cycle Time')}}" @if($show == null){ readonly }@endif required>
				            @if($errors->any())
				                <span role="alert">
	                                <strong style="color: red">{{$errors->first('Cycle_Time')}}</strong>
	                            </span>
				            @endif
				    	</div>
						<div class="form-group col-md-4"> 
				            <label for="CAV">{{__('CAV')}}</label>
				            <input type="number" step="1" value="{{old('CAV') ? old('CAV') : ($product ? $product->CAV : '') }}" class="form-control" id="CAV" name="CAV" placeholder="{{__('Enter')}} {{__('CAV')}}" @if($show == null){ readonly }@endif required>
				            @if($errors->any())
				                <span role="alert">
	                                <strong style="color: red">{{$errors->first('CAV')}}</strong>
	                            </span>
				            @endif
				    	</div>
					<div class="form-group col-md-8">
						<label for="symbolsProduct">{{__('Note')}}</label>
						<input type="text" value="{{old('Note') ? old('Note') : ($product ? $product->Note : '') }}" class="form-control" id="NoteProduct" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}" @if($show == null){ readonly }@endif >
						@if($errors->any())
							<span role="alert">
								<strong style="color: red">{{$errors->first('Note')}}</strong>
							</span>
						@endif
					</div>
				</div>         
			</div>
			<div class="card-footer">
				<a href="{{ route('masterData.product') }}" class="btn btn-info">{{__('Back')}}</a>
				<button type="submit" class="btn btn-success float-right"  @if($show == null){ hidden="hidden" }@endif>{{__('Save')}}</button>
			</div>
		</form>
	</div>
@endsection

@push('scripts')
	<script>
		$('.select2').select2()
	</script>
@endpush