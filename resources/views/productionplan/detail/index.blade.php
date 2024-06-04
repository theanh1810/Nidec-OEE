@extends('layouts.app')

@section('content')
    @if (Auth::user()->checkRole('delete_plan') || Auth::user()->level == 9999)
        @include('basic.modal_request_destroy', ['route' => route('productionplan.detail.destroy')])
    @endif
    @if (Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
        @include('productionplan.detail.modal_add_or_update')
    @endif
    @if (Auth::user()->checkRole('import_master') || Auth::user()->level == 9999)
        @include('basic.modal_import', [
            'route' => route('productionplan.detail.import_file_excel', [
                'Plan_ID' => $plan->ID,
                'Month' => $plan->Month,
                'Year' => $plan->Year,
            ]),
        ])
    @endif
    @include('basic.modal_table_history')
    <div class="p-3">
        <div class="card">
            <div class="card-header">
                <span class="text-bold" style="font-size: 23px">
                    {{ __('Plan') }} {{ __('Production') }} {{ __('Detail') }} : <span
                        style="color:Red;font-size: 23px">{{ $plan->Name }}</span>
                </span>
                <div class="card-tools">
                    {{-- @if (Auth::user()->checkRole('create_plan') || Auth::user()->level == 9999)
						<button class="btn btn-info btn-create">
							{{__('Create')}} {{ __('Plan') }} {{ __('Production') }}
						</button>
					@endif --}}
                    @if (Auth::user()->checkRole('import_plan') || Auth::user()->level == 9999)
                        <button class="btn btn-success btn-import" style="width: 180px">
                            {{ __('Import By File Excel') }}
                        </button>
                    @endif
                    @if (Auth::user()->checkRole('export_plan') || Auth::user()->level == 9999)
                        @if ($file_up)
                            <a href="{{ route('kitting.plan.export', ['ID' => $plan->ID]) }}" class="btn btn-success"
                                style="width: 180px">
                                {{ __('Export File Excel') }}
                            </a>
                        @endif
                    @endif
                    {{-- <a href="{{route('kitting.plan.visualization',['ID'=>$plan->ID])}}" class="btn btn-light" style="width: 180px">
							{{__('Visualization')}}
					</a> --}}

                    <button type="button" id="his-{{ $plan->ID }}" class="btn btn-warning btn-history"
                        style="width: 80px">
                        {{ __('History') }}
                    </button>

                </div>
            </div>
            <div class="card-body">
                <div class="h-space">
                    <input type="text" value="{{ $plan->ID }}" class="form-control d-none" id="idPlan"
                        name="ID" readonly>
                    <div class="form-group col-md-2">
                        <label>{{ __('Choose') }} {{ __('Machine') }}</label>
                        <select class="custom-select select2 machine" name="Machine">
                            <option value="">
                                {{ __('Choose') }} {{ __('Machine') }}
                            </option>
                            @foreach ($machine as $mac)
                                <option value="{{ $mac->ID }}" {{ $request->Machine == $mac->ID ? 'Selected' : '' }}>
                                    {{ $mac->Symbols }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{ __('Choose') }} {{ __('Product') }}</label>
                        <select class="custom-select select2 product" name="Product">
                            <option value="">
                                {{ __('Choose') }} {{ __('Product') }}
                            </option>
                            @foreach ($product as $pro)
                                <option value="{{ $pro->ID }}">
                                    {{ $pro->Symbols }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="form-group col-md-2">
            <label>{{ __('Choose') }} {{ __('Status') }}</label>
            <select class="custom-select select2 status" name="Status">
             <option value="">
              {{ __('Choose') }} {{ __('Status') }}
             </option>
             <option value="0">
              {{ __('Dont') }} {{ __('Production') }}
             </option>
             <option value="1">
              {{ __('Are') }} {{ __('Production') }}
             </option>
             <option value="2">
              {{ __('Success') }} {{ __('Production') }}
             </option>
            </select>
           </div> -->
                    <div class="form-group col-md-2">
                        <label>{{ __('From') }}</label>
                        <input type="date" class="form-control" id="from" name="From"
                            value="{{ $request->From }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label>{{ __('To') }}</label>
                        <input type="date" class="form-control" id="to" name="To"
                            value="{{ $request->To }}">
                    </div>
                    <button type="submit" class="w-10 btn btn-info filter">{{ __('Filter') }}</button>
                </div>
                @include('basic.alert')
                <div class="form-check">
                    <input type="checkbox" class="form-check-input list_status" id="exampleCheck1" value="0"
                        name="statusId" checked>
                    <label class="form-check-label" for="exampleCheck1">{{ __('Dont') }} {{ __('Production') }}</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input list_status" id="exampleCheck2" value="1"
                        name="statusId" checked>
                    <label class="form-check-label" for="exampleCheck2">{{ __('Are') }} {{ __('Production') }}</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input list_status" id="exampleCheck3" value="2"
                        name="statusId" checked>
                    <label class="form-check-label" for="exampleCheck3">{{ __('Success') }} {{ __('Production') }}</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input list_status" id="exampleCheck4" value="3"
                        name="statusId" checked>
                    <label class="form-check-label" for="exampleCheck4">{{ __('Tạm Dừng') }} {{ __('Production') }}</label>
                </div>
                <table class="table table-plan-detail table-bordered text-nowrap w-100"></table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalend" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title">{{ __('End') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kitting.plan.cancel') }}" method="get">
                    @csrf
                    <div class="modal-body">
                        <strong style="font-size: 23px">{{ __('Do You Want To End') }} <span id="nameDel"
                                style="color: blue"></span> ?</strong>
                        <input type="text" name="ID" id="idEnd" class="form-control d-none">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('End') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        var role_edit_plan = {{ Auth::user()->checkRole('update_plan') || Auth::user()->level == 9999 ? 1 : 0 }}
        var role_delete_plan = {{ Auth::user()->checkRole('delete_plan') || Auth::user()->level == 9999 ? 1 : 0 }}
        var role_end_plan = {{ Auth::user()->checkRole('end_plan') || Auth::user()->level == 9999 ? 1 : 0 }}
    </script>
    <script src="{{ asset('js/production-plan/detail.js') }}"></script>
@endpush
