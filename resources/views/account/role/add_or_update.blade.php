@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Role') }}
            </span>
        </div>
        <form role="from" method="post" action="{{ route('account.addOrUpdate.role') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 row text-center">
                        <div class="form-group col-md-12 hide">
                            <label for="idUnit">{{ __('ID') }}</label>
                            <input type="text" value="{{ old('id') ? old('id') : ($data ? $data->id : '') }}"
                                class="form-control" id="idUser" name="id" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="role">{{ __('Role') }}</label>
                            <input type="text" class="form-control" id="role" name="role"
                                placeholder="{{ __('Enter') }} {{ __('Role') }}" required>
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('role') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="des">{{ __('Description') }}</label>
                            <input type="text" class="form-control" id="des" name="des"
                                placeholder="{{ __('Enter') }} {{ __('Description') }}" required>
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="note">{{ __('Note') }}</label>
                            <input type="text" class="form-control" id="note" name="note"
                                placeholder="{{ __('Enter') }} {{ __('note') }}">
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('note') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('account.role') }}" class="btn btn-info" style="width: 80px">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-success float-right" style="width: 80px">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $('.select2').select2();
    </script>
@endpush
