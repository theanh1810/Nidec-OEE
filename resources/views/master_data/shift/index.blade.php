@extends('layouts.app')

@section('content')
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('masterData.shift.destroy')])
    @endif
    @include('basic.modal_table_history')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Shift') }}
            </span>
        </div>
        <div class="card-bordy p-3">
            <div class="h-space">
                <div class="form-group w-20">
                    <label>{{ __('Name') }} {{ __('Shift') }}</label>
                    <select class="custom-select select2 name" name="Name">
                        <option value="">
                            {{ __('Choose') }} {{ __('Name') }}
                        </option>
                        @foreach ($shift as $value)
                            <option value="{{ $value->Name }}">
                                {{ $value->Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-info w-10 filter">{{ __('Filter') }}</button>
                @if (Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
                    <a href="{{ route('masterData.shift.show') }}" class="btn btn-success w-10">
                        {{ __('Create') }}
                    </a>
                    <a href="#" class="btn btn-warning btn-history w-10">
                        {{ __('History') }}
                    </a>
                @endif
            </div>
            @include('basic.alert')
            <table class="table table-shift table-bordered table-striped text-nowrap w-100"></table>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/master-data/shift.js') }}"></script>
@endpush