@extends('layouts.app')

@push('myCss')
    <link rel="stylesheet" href="{{ asset('css/agvCss.css') }}">
@endpush

@section('content')
    <!-- Modal Load -->
    @include('control_agv.agv_control.modal-load')
    <!-- End Modal Load -->
    <!-- Modal request note -->
    @include('control_agv.agv_control.modal-request-note')
    <!-- End modal request note -->
    <!-- Modal detail -->
    @include('control_agv.agv_control.modal-detail')
    <!-- End modal detail -->
    <!-- Modal request delete -->
    @include('control_agv.agv_control.modal-request-delete')
    @include('control_agv.agv_control.modal-request-del-point')
    <!-- End modal request delete -->
    <!-- Modal create line -->
    @include('control_agv.agv_control.modal-create-line')
    <!-- End modal create line -->
    <!-- Modal create line point -->
    @include('control_agv.agv_control.modal-create-line-point')
    <!-- End modal create line point -->
    <!-- Modal Edit Point-->
    @include('control_agv.agv_control.modal-edit-point')
    <!-- End Modal Edit point-->
    <!-- Modal Edit Line-->
    @include('control_agv.agv_control.modal-edit-line')
    <!-- End Modal Edit Line-->
    <!-- Modal edit layout point -->
    @include('control_agv.agv_control.modal-edit-layout')
    <!-- End modal edit layout point -->
    <!-- Modal Loading -->
    @include('control_agv.agv_control.modal-loading')
    <!-- End Modal Loading -->
    @include('control_agv.agv_control.modal-request-del-tran')
    <!-- Modal Note -->
    @include('control_agv.agv_control.modal-note')

    <div class="content">
        <div class="container-fluid">
            <div class="row" style="padding: 0 !important">
                <div class="col-sm-2" style="padding-right: 0px;">
                    @include('control_agv.agv_control.display-agv')
                </div>
                <!-- /.col-md-6 -->
                <div class="col-sm-10" style="padding-left: 3px;">
                    <div class="card" id="my-card">
                        <div class="card-header">
                            <div class="row" style="vertical-align:middle;">
                                <span class="col-sm-4" style="font-weight: normal; font-size: 15px; margin: auto;">
                                    <div id="rat"></div>
                                </span>
                                <select id="layoutAgv" class="form-control select2 col-sm-5 form-control-sm"
                                    style="width: 50%;">
                                    <option value="0">{{ __('Select') }} {{ __('Layout') }}</option>
                                    @foreach ($layouts as $key => $value)
                                        <option value="{{ $value->ID }}" {{ $value->ID == $sel ? 'selected' : '' }}>
                                            {{ $value->Name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- card-tools -->
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-tool btn-sm" data-card-widget="maximize"
                                        data-animation-speed="100" id="maximize" style="float: right; margin: auto;"><i
                                            class="fas fa-expand"></i>
                                    </button>

                                    <button class="btn btn-success btn-sm" id="btnConfig"
                                        style="display: none; float: right; margin-right: 3px">
                                        <i class="fas fa-laptop"></i>
                                        {{ __('Config') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="myCanvan">
                            <div id="myCanvas" style="border: solid 1px; border-radius: 5px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- ps5 -->
        <!-- Server Socket -->
        <script src="{{ asset('plugins/socket/socket.io.min.js') }}"></script>
        <script src="{{ asset('dist/js/p5.min.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/agvControl.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/imgMap.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/imgTheme.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/pointMap.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/lineMap.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/imgAgv.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/point.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/line.js') }}"></script>
        <script src="{{ asset('js/control_agv/control/lineHide.js') }}"></script>
        {{-- <script src="{{ asset('js/control_agv/control/agvDisplay.js') }}"></script> --}}
    @endpush
@endsection
