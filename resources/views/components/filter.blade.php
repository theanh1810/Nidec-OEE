<div class="row">
    {{-- @dd($fil, $dat, $key, $name, $search); --}}
    @for($i = 0; $i < count($fil); $i++)
        @if($fil[$i] == 'select')
            <div class="form-group col-md-2">
                <label>
                    {{ __('Choose') }} {{ __($name[$i] ?? '') }}
                </label>
                <select 
                    class="custom-select select2 filters" 
                    name="{{ $nameSelect[$i] ?? ($name[$i] ?? '') }}" 
                    id="{{ $id[$i] ?? ($name[$i] ?? '') }}"
                >
                    <option value="">
                        {{ __('Choose') }} {{ __($name[$i] ?? '') }}
                    </option>
                    @foreach($dat[$i] as $value)
                        <option 
                            value="{{ $value[$key[$i] ?? 'id'] ?? '' }}"
                            {{ isset($_GET[$name[$i]]) ? ($_GET[$name[$i]] == $value[$key[$i]] ? 'selected' : '') : '' }}
                        >
                            {{ $value[$search[$i] ?? ''] ?? ($value[$searchReplace[$i] ?? ''] ?? '') }}
                        </option>
                    @endforeach
                </select>
            </div>
        @elseif($fil[$i] == 'input')

        @endif
    @endfor
    
    @if($from)
        <div class="form-group col-md-2">
            <label>{{__('From')}}</label>
            <input type="text" class="form-control" id="from" name="From" value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}">
            <span role="alert" class="hide from-to">
                <strong style="color: red">
                    {{__('Choose')}} {{__('Time')}} {{__('From')}} {{__('And')}} {{__('To')}}
                </strong>
            </span>
        </div>
    @endif
    
    @if($to)
        <div class="form-group col-md-2">
            <label>{{__('To')}}</label>
            <input type="text" class="form-control" id="to" name="To" value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}">
            <span role="alert" class="hide from-to">
                <strong style="color: red">
                    {{__('Choose')}} {{__('Time')}} {{__('From')}} {{__('And')}} {{__('To')}}
                </strong>
            </span>
        </div>
    @endif
    
    <div class="col-md-12" style="float: right; margin-bottom: 23px">
        <button type="submit" class="btn btn-info btn-filter"  style="width: 100px">
            {{__('Filter')}}
        </button>
        <button type="submit" class="btn btn-warning btn-reset"  style="width: 100px">
            {{__('Reset')}}
        </button>
    </div>
</div>