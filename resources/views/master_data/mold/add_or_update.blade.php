@extends('layouts.app')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold" style="font-size: 23px">
	                		{{ __('Mold') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('masterData.mold.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4 hide">
				                    <label for="idmold">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($mold ? $mold->ID : '') }}" class="form-control" id="idmold" name="ID" readonly>
				                </div>
				                <div class="form-group col-md-6">
				                    <label for="namemold">{{__('Name')}} {{ __('Mold') }}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($mold ? $mold->Name : '') }}" class="form-control" id="namemold" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-6">
				                    <label for="symbolsmold">{{__('Symbols')}} {{ __('Mold') }}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($mold ? $mold->Symbols : '') }}" class="form-control" id="symbolsmold" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-6">
				                    <label for="Quantity_Report">{{__('Cavity')}}  </label>
				                    <input type="Number" min = '0' value="{{old('CAV_Max') ? old('CAV_Max') : ($mold ? $mold->CAV_Max : '') }}" class="form-control" id="CAV_Max" name="CAV_Max"  required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>
								<div class="form-group col-md-6 ">
										<label for="symbolsProduct">{{__('Note')}}</label>
										<textarea type="text" class="form-control" id="NoteProduct" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}" >{{old('Note') ? old('Note') : ($mold ? $mold->Note : '') }}</textarea>
										@if($errors->any())
											<span role="alert">
												<strong style="color: red">{{$errors->first('Note')}}</strong>
											</span>
										@endif
									</div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('masterData.mold') }}" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
		                	<button type="submit" class="btn btn-success float-right" style="width: 80px">{{__('Save')}}</button>
		                </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>

@endsection
@push('scripts')
	<script>
		$('.select2').select2();
	</script>
@endpush