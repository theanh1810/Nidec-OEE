@extends('layouts.app')

@section('content')
    @if (Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('account.destroy')])
    @endif

    @if (Auth::user()->level == 9999)
        @include('basic.modal_reset_pass')
    @endif
    @include('basic.modal_table_history')

    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Account') }}
            </span>
        </div>
        <div class="card-bordy p-3">
            <div class="h-space">
                <div class="form-group w-20">
                    <label>{{ __('Choose') }} {{ __('User Name') }}</label>
                    <select class="custom-select select2 username" name="Symbols">
                        <option value="">
                            {{ __('Choose') }} {{ __('User Name') }}
                        </option>
                        @foreach ($data as $value)
                            <option value="{{ $value->username }}">
                                {{ $value->username }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-info w-10 filter">{{ __('Filter') }}</button>
                @if (Auth::user()->level == 9999)
                    <a href="{{ route('account.show') }}" class="btn btn-success" style="width: 180px">
                        {{ __('Create') }} {{ __('Account') }}
                    </a>
                    {{-- <a href="{{ route('account.role') }}" class="btn btn-secondary" style="width: 180px">
                        {{ __('Role') }} {{ __('Account') }}
                    </a> --}}
                @endif
                <a href="#" class="btn btn-warning btn-history w-10">
                    {{ __('History') }}
                </a>

            </div>
            @include('basic.alert')
            </br>
            <table class="table table-striped table-hover" id="tableUser" width="100%">
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var id_user = {{ Auth::user()->id }}
        var lvl_user = {{ Auth::user()->level }}
    </script>
    <script src="{{ asset('js/account/user.js') }}"></script>
@endpush
