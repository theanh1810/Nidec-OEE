@extends('layouts.app')

@section('content')
    @if (Auth::user()->checkRole('delete_master') || Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('masterData.machine.destroy')])
    @endif
    @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
        @include('basic.modal_import', ['route' => route('masterData.machine.importFileExcel')])
    @endif
    @include('basic.modal_table_history')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Machine') }}
            </span>
        </div>
        <div class="card-bordy p-3">
            <div class="h-space">
                <div class="form-group w-20">
                    <label>{{ __('Symbols') }} {{ __('Machine') }}</label>
                    <select class="custom-select select2 symbols" name="Symbols">
                        <option value="">
                            {{ __('Choose') }} {{ __('Symbols') }}
                        </option>
                        @foreach ($machine as $value)
                            <option value="{{ $value->Symbols }}">
                                {{ $value->Symbols }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-info w-10 filter">{{ __('Filter') }}</button>
                @if (Auth::user()->checkRole('create_master') || Auth::user()->level == 9999)
                    <a href="{{ route('masterData.machine.show') }}" class="btn btn-success w-10">
                        {{ __('Create') }}
                    </a>
                @endif
                @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
                    <button class="btn btn-success btn-import" style="width: 180px">
                        {{ __('Import By File Excel') }}
                    </button>
                @endif
                <a href="#" class="btn btn-warning btn-history w-10">
                    {{ __('History') }}
                </a>
            </div>
            @include('basic.alert')
            <table class="table table-machine table-bordered table-striped text-nowrap w-100"></table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/master-data/machine.js') }}"></script>
@endpush
