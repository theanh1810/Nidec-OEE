@extends('layouts.app')

@section('content')
    <div id="app" class="h-100">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/oee/visualization.js') }}"></script>
@endpush
