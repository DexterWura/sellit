@php
$content = getContent('login.content', true);
@endphp
@extends($activeTemplate.'layouts.app')
@section('app')
<section class="account-section">
    <div class="left">

        <div class="shape-top">
            <img src="{{ asset($activeTemplateTrue . 'images/shape1.png') }}" alt="image">
        </div>

        <div class="shape-bottom">
            <img src="{{ asset($activeTemplateTrue . 'images/shape1.png') }}" alt="image">
        </div>

        <div class="top w-100 text-center">
            <a href="{{ route('home') }}" class="account-logo"><img
                    src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="image"></a>
        </div>
        <div class="middle w-100">
            <form method="POST" action="{{ route('user.login') }}" onsubmit="return submitUserForm();">
                @csrf
                <div class="form-group">
                    <label>@lang('Email or Username ')<sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                        <input type="text" name="username" autocomplete="off" class="form--control"
                            placeholder="@lang('Enter your email or username')" value="{{ old('username') }}">
                        <i class="las la-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>@lang('Password') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                        <input type="password" name="password" autocomplete="off" class="form--control"
                            placeholder="@lang('Enter your password')">
                        <i class="las la-key"></i>
                    </div>
                </div>

                <div class="form-group">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember')
                        ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">@lang('Remember Me')</label>
                </div>

                <div class="form-group">
                    <div class="col-md-6 ">
                        @php echo loadReCaptcha() @endphp
                    </div>
                </div>
                @include($activeTemplate.'partials.custom_captcha')

                <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-white text-lg-start mt-2">
                            <a href="{{ route('user.password.request') }}" class="text--base">@lang('Forgot
                                Password?')</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-white text-lg-end mt-2">@lang('Haven\'t an account?') <a
                                href="{{ route('user.register') }}" class="text--base">@lang('Sign Up')</a> </p>
                    </div>
                </div>
            </form>
        </div>
        <div class="bottom w-100 text-center">
            @include($activeTemplate.'partials.copyright_text')
        </div>
    </div>
    <div class="right bg_img"
        style="background-image: url({{ getImage('assets/images/frontend/login/' . $content->data_values->background_image, '1360x910') }});">
        <div class="line-1"></div>
        <div class="line-2"></div>
        <div class="line-3"></div>
        <div class="line-4"></div>

        <div class="account-right-middle">
            <h3 class="title text-white fw-normal text-center"><b>{{ __($content->data_values->title) }}</b></h3>
            <p class="text-white fw-normal text-center">{{ $content->data_values->subtitle }}</p>
        </div>
    </div>
</section>
@endsection
@push('script')
<script>
    "use strict";

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
</script>
@endpush
