@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Account') }}
            </span>
        </div>
        <form role="from" method="post" action="{{ route('account.addOrUpdate') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 row text-center">
                        <div class="form-group col-md-12 hide">
                            <label for="idUnit">{{ __('ID') }}</label>
                            <input type="text" value="{{ old('id') ? old('id') : ($data ? $data->id : '') }}"
                                class="form-control" id="idUser" name="id" readonly>
                        </div>
                        @if (is_null($data))
                            <div class="form-group col-md-3">
                                <label for="username">{{ __('User Name') }}</label>
                                <input type="text" maxlength="20"
                                    value="{{ old('username') ? old('username') : ($data ? $data->username : '') }}"
                                    class="form-control" id="username" name="username"
                                    placeholder="{{ __('Enter') }} {{ __('User Name') }}" required>
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="form-group col-md-3">
                                <label for="username">{{ __('User Name') }}</label>
                                <input type="text" maxlength="20"
                                    value="{{ old('username') ? old('username') : ($data ? $data->username : '') }}"
                                    class="form-control" id="username" name="username"
                                    placeholder="{{ __('Enter') }} {{ __('User Name') }}" readonly>
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif
                        <div class="form-group col-md-3">
                            <label for="nameUser">{{ __('Name Username') }}</label>
                            <input type="text" value="{{ old('name') ? old('name') : ($data ? $data->name : '') }}"
                                class="form-control" id="nameUser" name="name"
                                placeholder="{{ __('Enter') }} {{ __('Name Username') }}" required maxlength="255">
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group col-md-3">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" value="{{ old('email') ? old('email') : ($data ? $data->email : '') }}"
                                class="form-control" id="email" name="email"
                                placeholder="{{ __('Enter') }} {{ __('Email') }}" required maxlength="255">
                            @if ($errors->any())
                                <span role="alert">
                                    <strong style="color: red">{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        @if (!is_null($data))
                            <div class="form-group col-md-3">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="{{ __('Enter') }} {{ __('Password') }}" maxlength="255">
                                @if ($errors->any())
                                    <span role="alert">
                                        <strong style="color: red">{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif

                    </div>
                    @if (Auth::user()->level == 9999)
                        @if (count($roles) > 0)
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body row" style="height: 100%">
                                        @foreach ($roles as $role)
                                            <div class="custom-control custom-checkbox col-3">
                                                <input class="custom-control-input" name="role[]" type="checkbox"
                                                    id="{{ $role->role }}" value="{{ $role->id }}"
                                                    {{ old('role') ? (in_array($role->id, old('role')) ? 'checked' : '') : ($data ? ($data->role->contains('id', $role->id) ? 'checked' : '') : '') }}>
                                                <label for="{{ $role->role }}" class="custom-control-label">
                                                    {{ $role->description }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif


                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('account') }}" class="btn btn-info" style="width: 80px">{{ __('Back') }}</a>
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
