@extends('layouts.app')

@section('content')
	<div class="card">
		<div class="card-header">
			<span class="text-bold" style="font-size: 23px">
				{{ __('Holiday') }}
			</span>
		</div>
		<form role="from" method="post" action="{{ route('masterData.holiday.addOrUpdate') }}">
			@csrf
			<div class="card-body">
				<div class="row"> 
					<div class="form-group col-md-4 d-none">
						<label for="idPlan">{{__('ID')}}</label>
						<input type="text" value="{{$Holiday ? $Holiday->ID : ''}}" class="form-control" id="idPlan" name="ID" readonly>
					</div>              
					<div class="form-group col-md-4">
						<label for="typeHoliday">{{__('Type')}} </label>
						<select name="Type" class="form-control select2 type" required>
							<option value="">
								{{__('Choose')}} {{__('Type')}}
							</option>
							<option value=1 {{$Holiday ? ($Holiday->Type == 1 ? 'selected' : '') : ''}}>
								{{__('Day')}}
							</option>
							<option value=2 {{$Holiday ? ($Holiday->Type == 2 ? 'selected' : '') : ''}}>
								{{__('Time')}}
							</option>
						</select>
						@if($errors->any())
							<span role="alert">
								<strong style="color: red">{{$errors->first('Type')}}</strong>
							</span>
						@endif
					</div>
					<div class="form-group col-md-4 {{$Holiday ? ($Holiday->Type == 1 ? 'hide' : '') : 'hide'}} type-2">
						<label>{{ __('Choose') }} {{ __('Time') }} {{ __('Start') }}</label>
						<input class="custom-select datetime" type="text"  name="from-2" value="{{old('Start_Time') ? old('Start_Time') : ($Holiday ? date('H:i:s', strtotime($Holiday->Start)) : '') }}">
					</div>
					<div class="form-group col-md-4 {{$Holiday ? ($Holiday->Type == 1 ? 'hide' : '') : 'hide'}} type-2">
						<label>{{ __('Choose') }} {{ __('Time') }} {{ __('End') }}</label>
						<input class="custom-select datetime1" type="text"  name="to-2" value="{{old('End_Time') ? old('End_Time') : ($Holiday ? date('H:i:s', strtotime($Holiday->End)) : '') }}">
					</div>
					<div class="form-group col-md-4 {{$Holiday ? ($Holiday->Type == 2 ? 'hide' : '') : 'hide'}} type-1">
						<label>{{ __('Choose') }} {{ __('Time') }} {{ __('Start') }}</label>
						<input class="custom-select" type="time" step="1"  name="from-1" value="{{old('Start_Time') ? old('Start_Time') : ($Holiday ? date('H:i:s', strtotime($Holiday->Start)) : '') }}">
					</div>
					<div class="form-group col-md-4 {{$Holiday ? ($Holiday->Type == 2 ? 'hide' : '') : 'hide'}} type-1">
						<label>{{ __('Choose') }} {{ __('Time') }} {{ __('End') }}</label>
						<input class="custom-select" type="time" step="1"  name="to-1" value="{{old('End_Time') ? old('End_Time') : ($Holiday ? date('H:i:s', strtotime($Holiday->End)) : '') }}">
					</div>
					<div class="form-group col-md-12">
					<label for="symbolsPlan">{{__('Note')}}</label>
					<input type="text" value="{{old('Note') ? old('Note') : '' }}" class="form-control" id="NotePlan" name="Note" placeholder="{{__('Enter')}} {{__('Note')}}">
					</div>
				</div>         
			</div>
			<div class="card-footer">
				<a href="{{ route('masterData.holiday') }}" class="btn btn-info" style="width: 80px">{{__('Back')}}</a>
				<button type="submit" class="btn btn-success float-right" style="width: 80px">{{__('Save')}}</button>
			</div>
		</form>
	</div>
@endsection

@push('scripts')
	<script>
		$('.select2').select2();
		$('.datetime').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        })

        $('.datetime1').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            startDate: moment().startOf('hour').add(1, 'day'),
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        })
		$('.type').on('input',function(){
			let type = $(this).val()
			if(type == '1')
			{
				$('.type-1').show()
				$('.type-2').hide()
			}else if(type == '2')
			{
				$('.type-2').show()
				$('.type-1').hide()
			}
			else
			{
				$('.type-1').hide()
				$('.type-2').hide()
			}
		})
	</script>
@endpush