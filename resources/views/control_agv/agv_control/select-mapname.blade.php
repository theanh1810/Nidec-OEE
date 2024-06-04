<i class="fas fa-times-circle"  id="errMapNamePoint" style="display:none; color:red"></i>
<select id="mapName" class="form-control select2 select2-hidden-accessible select-location col-sm-12 {{$class}}" style="width: 100%;" tabindex="-1" aria-hidden="true">
	<option value="0">{{__('Select')}}</option>
	  @foreach($maps as $key => $value)
		<option class="sele my-sel{{$value->Layout}} del{{$value->NAME}}" value="{{$value->NAME}}">{{$value->NAME}}</option>
	@endforeach
</select>