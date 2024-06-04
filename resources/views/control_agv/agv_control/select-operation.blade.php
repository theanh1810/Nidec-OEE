<select id="operation" class="form-control select2 select2-hidden-accessible select-location col-sm-12 {{$class}}" style="width: 100%;" tabindex="-1" aria-hidden="true">
	<option value="0">{{__('Select')}} {{__('Operation')}}</option>
	  @foreach($operation as $key => $value)
		<option class="my-type type-{{$value->agv_type}}" value="{{$value->ID}}">
			{{$value->Des}}
		</option>
	@endforeach
</select>
<i class="fas fa-times-circle"  id="precodeErr" style="display:none; color:red">{{__('Select')}} {{__('Operation')}}</i>
