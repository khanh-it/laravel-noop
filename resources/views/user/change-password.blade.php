@push('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-push-3 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('Change Password') }}</div>

                <div class="panel-body">
                    <form method="POST" action="{{ route('user::change-password:do') }}" aria-label="{{ __('Change Password') }}">
                        @csrf

                        <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label text-md-right">{{ __('New Password') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation" class="col-md-4 control-label text-md-right">{{ __('New Password Retype') }}</label>

                            <div class="col-md-8">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block" role="alert">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 col-md-push-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Change Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush
@extends('layouts.app')
