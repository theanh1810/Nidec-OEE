<div class="form-group col-sm-6 row" style="float: left;">
    <span class="col-md-5 my-span">{{__('Layout')}}</span>
    <select id="selectLayout" class="form-control form-control-sm col-md-7 select2 select-location select-layout" style="" tabindex="-1" aria-hidden="true">
        <option value="0">{{__('Select')}} {{__('Layout')}}</option>
        @foreach($layouts as $key => $value)
            <option value="{{$value->ID}}" {{($value->ID == $sel)? "selected" : ""}}> 
                {{$value->Name}} 
            </option>
        @endforeach
    </select>
    <i class="fas fa-times-circle"  id="errNamePoint" style="display:none; color:red"></i>  
</div>