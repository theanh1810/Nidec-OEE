@extends('layouts.app')

@section('content')
    @if (Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('account.destroy.role')])
    @endif

    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Role') }}
            </span>
        </div>
        <div class="card-bordy p-3">
            <div class="h-space">
                @if (Auth::user()->level == 9999)
                    <a href="{{ route('account.show.role') }}" class="btn btn-success" style="width: 180px">
                        {{ __('Create') }} {{ __('Role') }}
                    </a>
                @endif

            </div>
            @include('basic.alert')
            </br>
            <table class="table table-striped table-hover" id="tableRole" width="100%">
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/account/role.js') }}"></script>
@endpush
