@extends('layout.layout')

@section('login')
    <div class="peers ai-s fxw-nw h-100vh justify-content-center">
        <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style='min-width: 320px;'>
            <div class="form_box">
            <h2 class="fw-300 c-grey-900 mB-40 text-center"><strong>{{ __('admin.auth.mypet_admin') }}</strong></h2>
            <h4 class="fw-300 c-grey-900 mB-40 text-center">{{ __('admin.auth.welcome_back') }}</h4>
            
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                <div class="form-group">
                <label class="text-normal text-dark">{{ __('admin.auth.username') }}</label>
                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label class="text-normal text-dark">{{ __('admin.auth.password') }}</label>
                    <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <div class="peers ai-c jc-sb fxw-nw">
                        <div class="peer">
                            <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                                <input checked type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer" {{ old('remember') ? 'checked' : ''}}>
                                <label for="inputCall1" class=" peers peer-greed js-sb ai-c">
                                    <span class="peer peer-greed">{{ __('admin.auth.remember_me') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="peer">
                            <button class="btn btn-primary">{{ __('admin.auth.login') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
@endsection
