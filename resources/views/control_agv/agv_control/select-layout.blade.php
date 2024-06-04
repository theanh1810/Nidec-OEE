<select id="layoutAgv" class="form-control select2 select2-hidden-accessible select-location {{$class}}" style="" tabindex="-1" aria-hidden="true">
	<option value="0">{{__('Select')}} {{__('Layout')}}</option>
		@foreach($layouts as $key => $value)
		<option value="{{$value->ID}}" {{($value->ID == $sel)? "selected" : ""}}> {{$value->Name}} </option>
	@endforeach
</select>