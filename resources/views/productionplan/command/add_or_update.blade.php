@extends('layouts.app')

@section('content')
	<div>
	    <div class="row justify-content-center">
	        <div class="col-md-10">
	            <div class="card">
	                <div class="card-header">
	                	<span class="text-bold">
	                		{{ __('Plan') }}
	                	</span>
	                </div>
	                <form role="from" method="post" action="{{ route('kitting.plan.addOrUpdate') }}">
	                	@csrf
		                <div class="card-body">
		                	<div class="row">               
		                    	<div class="form-group col-md-4 hide">
				                    <label for="idPlan">{{__('ID')}}</label>
				                    <input type="text" value="{{old('ID') ? old('ID') : ($plan ? $plan->ID : '') }}" class="form-control" id="idPlan" name="ID" readonly>
				                </div>
				                <div class="form-group col-md-6">
				                    <label for="namePlan">{{__('Name')}} {{ __('Plan') }}</label>
				                    <input type="text" value="{{old('Name')? old('Name') : ($plan ? $plan->Name : '') }}" class="form-control" id="namePlan" name="Name" placeholder="{{__('Enter')}} {{__('Name')}}" >
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Name')}}</strong>
	                                    </span>
				                    @endif
				                </div>
				                <div class="form-group col-md-6">
				                    <label for="symbolsPlan">{{__('Symbols')}} {{ __('Plan') }}</label>
				                    <input type="text" value="{{old('Symbols') ? old('Symbols') : ($plan ? $plan->Symbols : '') }}" class="form-control" id="symbolsPlan" name="Symbols" placeholder="{{__('Enter')}} {{__('Symbols')}}" required>
				                    @if($errors->any())
				                    	<span role="alert">
	                                        <strong style="color: red">{{$errors->first('Symbols')}}</strong>
	                                    </span>
				                    @endif
				                </div>

				                <div class="form-group col-md-12">
				                    <label for="symbolsPlan">{{__('Note')}}</label>
				                    <input type="text" value="{{old('Note') ? old('Note') : ($plan ? $plan->Note : '') }}" class="form-control" id="NotePlan" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}">
				                </div>
			                </div>         
		                </div>
		                <div class="card-footer">
		                	<a href="{{ route('kitting.plan') }}" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
		                	<button type="submit" class="btn btn-success float-right" style="width: 80px">{{__('Save')}}</button>
		                </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
@endsection