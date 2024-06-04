@extends('layouts.app')

@section('content')
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_des', [
            'route' => route('destroy.trans'),
        ])
        @include('basic.modal_request_success', [
            'route' => route('success.trans'),
        ])
    @endif
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('List') }} {{ __('Command') }} {{ __('AGV') }}
            </span>
        </div>
        <div class="card-bordy p-3">
            <div class="h-space">
                <div class="form-group w-15">
                    <label>{{ __('AGV') }} </label>
                    <select class="custom-select select2 agv" id="agv" name="AGV">
                        <option value="">
                            {{ __('Choose') }} {{ __('AGV') }}
                        </option>
                        <option value="0">
                            {{ __('Do Not Have') }} {{ __('AGV') }}
                        </option>
                        @foreach ($agvs as $value)
                            <option value="{{ $value->ID }}">
                                {{ $value->Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group w-15">
                    <label>{{ __('Material Return Location') }} </label>
                    <select class="custom-select select2 machine" name="Machine">
                        <option value="">
                            {{ __('Choose') }} {{ __('Material Return Location') }}
                        </option>
                        @foreach ($machines as $value)
                            <option value="{{ $value->ID }}">
                                {{ $value->Symbols }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group w-15">
                    <label>{{ __('Status') }} </label>
                    <select class="custom-select select2 status" name="Status">
                        <option value="">
                            {{ __('Choose') }} {{ __('Status') }}
                        </option>
                        <option value="0">
                            {{ __('Find AGV') }}</option>
                        <option value="1">
                            {{ __('Processing') }}</option>
                        <option value="2">
                            {{ __('Success') }}</option>
                        <option value="5">
                            {{ __('Destroy') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info w-10 filter">{{ __('Filter') }}</button>
            </div>
            <table class="table table-bordered table-striped text-nowrap w-100"></table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var role_delete_Trans = {{ Auth::user()->checkRole('delete_Trans') || Auth::user()->level == 9999 ? 1 : 0 }}
    </script>
    <script src="{{ asset('js/control_agv/list_command.js') }}"></script>
@endpush
