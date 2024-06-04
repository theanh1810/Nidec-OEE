@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Shift') }}
            </span>
        </div>

        <form role="from" method="post" action="{{ route('masterData.shift.addOrUpdate') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4 d-none">
                        <label for="idShift">{{ __('ID') }}</label>
                        <input type="text" value="{{ old('ID') ? old('ID') : ($shift ? $shift->ID : '') }}"
                            class="form-control" id="idShift" name="ID" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ShiftName">{{ __('Name') }} {{ __('Shift') }}</label>
                        <div class="input-group">
                            <input type="text" value="{{ old('Name') ? old('Name') : ($shift ? $shift->Name : '') }}"
                                class="form-control" id="ShiftName" maxlength="20" name="Name"
                                placeholder="{{ __('Enter') }} {{ __('Name') }}"
                                @if ($show == null) { readonly } @endif required>
                            <div class="input-group-append">
                                <span class="input-group-text" style="color:Red">*</span>
                            </div>
                        </div>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('Choose') }} {{ __('Time') }} {{ __('Start') }}</label>
                        <input class="custom-select" type="time" step="1" name="from" required
                            value="{{ old('Start_Time') ? old('Start_Time') : ($shift ? date('H:i:s', strtotime($shift->Start_Time)) : '') }}">
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Start_Time') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('Choose') }} {{ __('Time') }} {{ __('End') }}</label>
                        <input class="custom-select" type="time" step="1" name="to" required
                            value="{{ old('End_Time') ? old('End_Time') : ($shift ? date('H:i:s', strtotime($shift->End_Time)) : '') }}">
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('End_Time') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('ID') }} {{ __('Shift') }}</label>
                        <input class="form-control" type="number" name="Shift" min="0" step="1"
                            value="{{ old('Shift') ? old('Shift') : ($shift ? $shift->Shift : '') }}" required>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Shift') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-md-6 ">
                        <label for="symbolsProduct">{{ __('Note') }}</label>
                        <textarea type="text" class="form-control" id="NoteProduct" name="Note"
                            placeholder="{{ __('Enter') }} {{ __('Note') }}">{{ old('Note') ? old('Note') : ($shift ? $shift->Note : '') }}</textarea>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Note') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('masterData.shift') }}" class="btn btn-info">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-success float-right"
                    @if ($show == null) { hidden="hidden" } @endif>{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection
