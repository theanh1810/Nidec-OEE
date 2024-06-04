@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Status') }}
            </span>
        </div>
        <form role="from" method="post" action="{{ route('masterData.status.addOrUpdate') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4 d-none">
                        <label for="idShift">{{ __('Old ID') }}</label>
                        <input type="text"
                            value="{{ old('Old_ID') ? old('Old_ID') : ($status ? $status->Old_ID : '') }}"
                            class="form-control" id="idShift" name="Old_ID" readonly>
                    </div>
                    @if ($status)
                        @if ($status->ID == 1 || $status->ID == 0 || $status->ID == 2 || $status->ID == 3)
                            <div class="form-group col-md-4 ">
                                <label for="idShift">{{ __('ID ') }}</label>
                                <input type="number"
                                    value="{{ old('ID') ? old('ID') : ($status ? $status->ID : '') }}"
                                    class="form-control" id="idShift" name="ID" readonly>
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('ID') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nameStatus">{{ __('Name') }} {{ __('Status') }}</label>
                                <input type="text"
                                    value="{{ old('Name') ? old('Name') : ($status ? $status->Name : '') }}"
                                    class="form-control" id="nameStatus" name="Name"
                                    placeholder="{{ __('Enter') }} {{ __('Name') }}" readonly>
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('Name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="form-group col-md-4 ">
                                <label for="idShift">{{ __('ID ') }}</label>
                                <input type="number"
                                    value="{{ old('ID') ? old('ID') : ($status ? $status->ID : '') }}"
                                    class="form-control" id="idShift" name="ID">
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('ID') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nameStatus">{{ __('Name') }} {{ __('Status') }}</label>
                                <input type="text"
                                    value="{{ old('Name') ? old('Name') : ($status ? $status->Name : '') }}"
                                    class="form-control" id="nameStatus" name="Name"
                                    placeholder="{{ __('Enter') }} {{ __('Name') }}" required>
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('Name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="form-group col-md-4 ">
                            <label for="idShift">{{ __('ID ') }}</label>
                            <input type="number"
                                value="{{ old('ID') ? old('ID') : ($status ? $status->ID : '') }}"
                                class="form-control" id="idShift" name="ID">
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('ID') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nameStatus">{{ __('Name') }} {{ __('Status') }}</label>
                            <input type="text"
                                value="{{ old('Name') ? old('Name') : ($status ? $status->Name : '') }}"
                                class="form-control" id="nameStatus" name="Name"
                                placeholder="{{ __('Enter') }} {{ __('Name') }}" required>
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('Name') }}</strong>
                                </span>
                            @endif
                        </div>
                    @endif
                    
                    <div class="form-group col-md-4">
                        <label for="nameStatus">{{ __('Symbols') }} {{ __('Status') }}</label>
                        <input type="text"
                            value="{{ old('Symbols') ? old('Symbols') : ($status ? $status->Symbols : '') }}"
                            class="form-control" id="nameStatus" name="Symbols"
                            placeholder="{{ __('Enter') }} {{ __('Symbols') }}" required>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Symbols') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label for="DescriptionStatus">{{ __('Description') }} {{ __('Status') }}</label>
                        <textarea type="text" class="form-control" id="DescriptionStatus" name="Note"
                            placeholder="{{ __('Enter') }} {{ __('Description') }}">{{ old('Note') ? old('Note') : ($status ? $status->Note : '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('masterData.status') }}" class="btn btn-info">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-success float-right"
                    @if ($show == null) { hidden="hidden" } @endif>{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection
