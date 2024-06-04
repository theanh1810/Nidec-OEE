<select id="chargPoint" class="form-control select2 select2-hidden-accessible select-location col-sm-12 {{$class}}" style="width: 100%;" tabindex="-1" aria-hidden="true">
	<option value="0">{__{('Select')}} {__{('Location')}}</option>
	@foreach($point as $key1 => $value1)
		<option class="my-type point-type-{{$value1->AGV_TYPE}} pointLocation{{$value1->ID}} layout{{$value1->Layout}}" value="{{$value1->ID}}">
			{{$value1->NAME}}
		</option>
	@endforeach
</select>
<i class="fas fa-times-circle"  id="pointErr" style="display:none; color:red">
	{__{('Select')}} {__{('Location')}}
</i>
