<div class="row {{ $myClass }}" style="width: 100%; margin: 5px auto 5px auto;">
    <span class="{{ $col0 }} my-span">{{ $name }}</span>
    <input type="{{ $myType }}" class="form-control form-control-sm {{ $col1 }}" {{ $idShow }} value="{{ $value }}" {{ $read ? '' : 'readonly' }} placeholder="{{ $place }}" name="{{ $myName }}" {{ $required ? 'required' : '' }} step="{{ $step }}" min="{{ $min }}" max="{{ $max }}">
    <span class="text-danger my-span {{ $showMessage ? '' : 'hide' }}" id="{{ $idMess }}">{{ $message }}</span>
</div>