@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
                <div class="card custom--card top--border-base overflow-visible">
                    <div class="card-body overflow-visible">
                        <form method="POST" action="{{ route('user.password.update') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group hover-input-popup">
                                <label>@lang('Password')</label>
                                <div class="custom-icon-field">
                                    <i class="las la-lock"></i>
                                    <input type="password" name="password"
                                        class="form--control @error('password') is-invalid @enderror" required>
                                    @if ($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Confirm Password')</label>
                                <div class="custom-icon-field">
                                    <i class="las la-lock"></i>
                                    <input type="password" class="form--control" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <input type="submit" class="mt-2 btn btn--base w-100 text-center"
                                    value="@lang('Submit')">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
<script>
    (function ($) {
        "use strict";
        @if($general->secure_password)
            $('input[name=password]').on('input',function(){
                secure_password($(this));
            });
        @endif
    })(jQuery);
</script>
@endpush
