@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="text-bold" style="font-size: 23px">
                {{ __('Line') }}
            </span>
            <div class="card-tools">
				<div>
					<p><span style="color:red"> * </span> <span>: {{__('Required information (maximum 20 characters)')}} </span> </p>
				</div>
			</div>
        </div>
        <form role="from" method="post" action="{{ route('masterData.line.addOrUpdate') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12 d-none">
                        <label for="idline">{{ __('ID') }}</label>
                        <input type="text" value="{{ old('ID') ? old('ID') : ($line ? $line->ID : '') }}"
                            class="form-control" id="idline" name="ID" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="nameline">{{ __('Name') }} {{ __('Line') }}</label>
                        <div class="input-group">
                            <input type="text" maxlength="25"
                                value="{{ old('Name') ? old('Name') : ($line ? $line->Name : '') }}"
                                class="form-control" id="nameline" name="Name"
                                placeholder="{{ __('Enter') }} {{ __('Name') }}" required>
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
                    <div class="form-group col-md-6 ">
                        <label for="NoteProduct">{{ __('Note') }}</label>
                        <textarea type="text" class="form-control" id="NoteProduct" name="Note"
                            placeholder="{{ __('Enter') }} {{ __('Note') }}">{{ old('Note') ? old('Note') : ($line ? $line->Note : '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('masterData.line') }}" class="btn btn-info"
                    style="width: 80px">{{ __('Back') }}</a>
                <button type="submit" class="float-right btn btn-success" style="width: 80px">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection
