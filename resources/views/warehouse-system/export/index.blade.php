@extends('layouts.app')

@section('content')
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_cancel', [
            'route' => route('warehouse_system.export_materials.cancel'),
        ])
    @endif
    @if (Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
        @include('warehouse-system.export.modal_export', [
            'route' => route('warehouse_system.export_materials.export'),
        ])
    @endif
    @include('basic.modal_alert_export_materials')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Export') }} {{ __('Materials') }}
            </span>
        </div>
        <div class="card-bordy p-3">
            <div class="h-space">
                <div class="form-group w-20 d-none">
                    <label>{{ __('Symbols') }} {{ __('Materials') }}</label>
                    <select class="custom-select select2 materials" name="Symbols">
                        <option value="">
                            {{ __('Choose') }} {{ __('Symbols') }}
                        </option>
                        @foreach ($materials as $value)
                            <option value="{{ $value->ID }}">
                                {{ $value->Symbols }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group w-20">
                    <label>{{ __('Name') }} {{ __('Machine') }}</label>
                    <select class="custom-select select2 machine" name="Name">
                        <option value="">
                            {{ __('Choose') }} {{ __('Name') }}
                        </option>
                        @foreach ($machine as $value)
                            <option value="{{ $value->ID }}">
                                {{ $value->Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>{{ __('Choose') }} {{ __('Type') }}</label>
                    <select class="custom-select select2 Type" name="Type">
                        <option value="">
                            {{ __('Choose') }} {{ __('Type') }}
                        </option>
                        <option value="1">
                            {{ __('AGV') }}
                        </option>
                        <option value="2">
                            {{ __('Normal') }}
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>{{ __('Choose') }} {{ __('Status') }}</label>
                    <select class="custom-select select2 status" name="Status">
                        <option value="">
                            {{ __('Choose') }} {{ __('Status') }}
                        </option>
                        <option value="0">
                            {{ __('Dont') }} {{ __('Export') }}
                        </option>
                        <option value="1">
                            {{ __('Are') }} {{ __('Export') }} ( {{ __('Waiting for AGV') }})
                        </option>
                        <option value="2">
                            {{ __('Are') }} {{ __('Export') }} ( {{ __('AGV Shipping') }})
                        </option>
                        <option value="3">
                            {{ __('Success') }} {{ __('Export') }}
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info w-10 filter">{{ __('Filter') }}</button>
            </div>
            @include('basic.alert')
            <table class="table table-bordered table-striped text-nowrap w-100"></table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/warehouse-system/export-materials.js') }}"></script>
@endpush
