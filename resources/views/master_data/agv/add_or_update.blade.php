@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('AGV') }}
            </span>
            <div class="card-tools">
                <div>
                    <p><span style="color:red"> * </span> <span>: {{ __('Required information (maximum 20 characters)') }}
                        </span> </p>
                </div>
            </div>
        </div>
        <form role="from" method="post" action="{{ route('masterData.agv.addOrUpdate') }}" enctype="multipart/form-data"
            id="uploadForm">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4" style="display:none">
                        <label for="idAGV">{{ __('ID') }}</label>
                        <input type="text" value="{{ old('ID') ? old('ID') : ($agv ? $agv->ID : '') }}"
                            class="form-control" id="idAGV" name="ID" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="nameAGV">{{ __('Name') }} {{ __('AGV') }}</label>
                        <div class="input-group">
                            <input type="text" value="{{ old('Name') ? old('Name') : ($agv ? $agv->Name : '') }}"
                                class="form-control" id="nameAGV" name="Name"
                                placeholder="{{ __('Enter') }} {{ __('Name') }}" maxlength="20" required>
                            <div class="input-group-append">
                                <span class="input-group-text" style="color:Red">*</span>
                            </div>
                        </div>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="macAGV">{{ __('MAC') }} {{ __('AGV') }}</label>
                        <div class="input-group">
                            <input type="text" value="{{ old('MAC') ? old('MAC') : ($agv ? $agv->MAC : '') }}"
                                class="form-control" id="macAGV" name="MAC"
                                placeholder="{{ __('Enter') }} {{ __('MAC') }}" maxlength="20">
                            <div class="input-group-append">
                                <span class="input-group-text" style="color:Red">*</span>
                            </div>
                        </div>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('MAC') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="typeAGV">{{ __('Type') }} {{ __('AGV') }}</label>
                        <select name="Type" class="form-control select2" id="typeAGV" required>
                            <option value="0">
                                {{ __('Choose') }} {{ __('Type') }} {{ __('AGV') }}
                            </option>
                            <option value="1" {{ $agv ? ($agv->Type == 1 ? 'selected' : '') : '' }}>NAV
                            </option>
                            <option value="2" {{ $agv ? ($agv->Type == 2 ? 'selected' : '') : '' }}>
                                SHIV
                                TYPE</option>
                            <option value="3" {{ $agv ? ($agv->Type == 3 ? 'selected' : '') : '' }}>AMR
                            </option>
                        </select>
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Type') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group col-md-5">
                        <label for="Active">{{ __('Active') }} {{ __('AGV') }}</label>
                        <select name="Active" class="form-control select2" required>
                            <option value="2" {{ $agv ? ($agv->Active == 2 ? 'selected' : '') : '' }}>
                                {{ __('Enable') }}
                            </option>
                            <option value="1" {{ $agv ? ($agv->Active == 1 ? 'selected' : '') : '' }}>
                                {{ __('Disable') }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-7 ">
                        <label for="symbolsProduct">{{ __('Note') }}</label>
                        <input type="text" class="form-control" id="NoteProduct" name="Note"
                            placeholder="{{ __('Enter') }} {{ __('Note') }}"
                            value="{{ old('Note') ? old('Note') : ($agv ? $agv->Note : '') }}"">
                        @if ($errors->any())
                            <span role="alert">
                                <strong style="color: red">{{ $errors->first('Note') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('masterData.agv') }}" class="btn btn-info" style="width: 80px">
                    {{ __('Back') }}
                </a>
                <button type="submit" class="btn btn-success float-right" style="width: 80px">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $('.select2').select2()

        let from = $('#maintenanceDate').val();
        let to = $('#maintenanceTime').val();

        if (from == '') {
            from = moment();
        }

        if (to == '') {
            to = moment();
        }

        $('#maintenanceDate').daterangepicker({
            timePicker: false,
            singleDatePicker: true,
            showDropdowns: true,
            applyButtonClasses: 'btn-primary',
            cancelButtonClasses: 'btn-secondary',
            startDate: moment(from).format('MM/DD/YYYY'),
            locale: {
                // format     : 'DD/MM/YYYY',
                cancelLabel: __calculate.cancel,
                applyLabel: __calculate.apply
            }
        });

        $('#maintenanceTime').daterangepicker({
            timePicker: false,
            singleDatePicker: true,
            showDropdowns: true,
            applyButtonClasses: 'btn-primary',
            cancelButtonClasses: 'btn-secondary',
            startDate: moment(to).format('MM/DD/YYYY'),
            locale: {
                // format     : 'DD/MM/YYYY',
                cancelLabel: __calculate.cancel,
                applyLabel: __calculate.apply
            }
        });
    </script>
@endpush
