@extends('layouts.app')

@section('content')
    @include('control_agv.efficiency.modal.modal_agv')
    @include('control_agv.efficiency.modal.modal_note')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div id="app"></div>
            <div class="col-12">
                <div class="col-lg-12 row text-center">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-2">
                        <button class="btn btn-success btn-choose-agv" style="margin: 33px 0px 10px 0px;">
                            {{ __('Choose') }} {{ __('AGV') }}
                        </button>
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{ __('From') }}</label>
                        <input type="text" class="form-control" id="from" name="From"
                            value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}">
                        <span role="alert" class="hide from-to">
                        </span>
                    </div>

                    <div class="form-group col-md-2">
                        <label>{{ __('To') }}</label>
                        <input type="text" class="form-control" id="to" name="To"
                            value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}">
                        <span role="alert" class="hide from-to">
                        </span>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-warning btn-note-agv" style="margin: 33px 0px 10px 0px;">
                            {{ __('Note') }}
                        </button>
                    </div>
                    {{-- <div class="col-lg-2">
        				<a href="{{ route('controlAGV.transportSystem.listErrorAGV') }}" class="btn btn-danger" style="margin: 33px 0px 10px 0px;">
        					{{__('Error')}} {{__('AGV')}}
        				</a>
        			</div> --}}
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title text-bold" style="font-size: 23px">{{ __('Efficiency Chart') }}</h3>
                            {{-- <div class="card-tools">
				              	<a href="{{ route('controlAGV.transportSystem.newChart') }}" class="btn btn-info">
				              		{{__('Timeline Chart')}}
				              	</a>
			              	</div> --}}
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <canvas id="efficiency-chart" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title text-bold" style="font-size: 23px">
                                    {{ __('Ratio Chart') }} {{ __('Error') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="efficiency-chart-error" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title text-bold" style="font-size: 23px">
                                    {{ __('Efficiency Chart') }} {{ __('Total') }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="efficiency-chart-total" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/efficiencyAGV.js') }}"></script>

    <script>
        let from = '';
        let to = '';

        if (from == '') {
            from = moment().startOf('month').format('DD/MM/YYYY');
        }

        if (to == '') {
            to = moment().format('DD/MM/YYYY');
        }

        $('#from').daterangepicker({
            timePicker: false,
            singleDatePicker: true,
            showDropdowns: true,
            applyButtonClasses: 'btn-primary',
            cancelButtonClasses: 'btn-secondary',
            startDate: moment(from, 'DD/MM/YYYY'),
            locale: {
                // format     : 'DD/MM/YYYY',
                cancelLabel: __calculate.cancel,
                applyLabel: __calculate.apply
            }
        });

        $('#to').daterangepicker({
            timePicker: false,
            singleDatePicker: true,
            showDropdowns: true,
            applyButtonClasses: 'btn-primary',
            cancelButtonClasses: 'btn-secondary',
            startDate: moment(to, 'DD/MM/YYYY'),
            locale: {
                // format     : 'DD/MM/YYYY HH:mm:ss',
                cancelLabel: __calculate.cancel,
                applyLabel: __calculate.apply
            }
        });

        $('.btn-choose-agv').on('click', function() {
            $('#modalAGV').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('.btn-note-agv').on('click', function() {
            $('#modalNote').modal(); //{backdrop: 'static', keyboard: false}
        });

        // var socket = io('http://localhost:8090');

        // socket.on('chat', function(msg) {
        //     console.log(msg);
        //     $('#socket').append(`
    //       <span>`+msg+`</span>
    //     `);
        //     // var item = document.createElement('li');
        //     // item.textContent = msg;
        //     // messages.appendChild(item);
        //     // window.scrollTo(0, document.body.scrollHeight);
        // });
    </script>
@endpush
