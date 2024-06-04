@extends('layouts.app')

@section('content')
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('masterData.line.destroy')])
    @endif
    @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
        @include('basic.modal_import', ['route' => route('masterData.line.importFileExcel')])
    @endif
    @include('basic.modal_table_history')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Line') }}
            </span>
        </div>
        <div class="p-3 card-bordy">
            <div class="h-space">
                <div class="w-20 form-group">
                    <label>{{ __('Name') }} {{ __('Line') }}</label>
                    <select class="custom-select select2 name" name="Name">
                        <option value="">
                            {{ __('Choose') }} {{ __('Name') }}
                        </option>
                        @foreach ($line as $value)
                            <option value="{{ $value->Name }}">
                                {{ $value->Name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-10 btn btn-info filter">{{ __('Filter') }}</button>
                @if (Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
                    <a href="{{ route('masterData.line.show') }}" class="w-10 btn btn-success">
                        {{ __('Create') }}
                    </a>
                @endif
                @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                    <button class="btn btn-success btn-import" style="width: 180px">
                        {{ __('Import By File Excel') }}
                    </button>
                @endif
                <a href="#" class="w-10 btn btn-warning btn-history">
                    {{ __('History') }}
                </a>
            </div>
            @include('basic.alert')
            <table class="table table-line table-bordered table-striped text-nowrap w-100"></table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/master-data/line.js') }}"></script>
@endpush
