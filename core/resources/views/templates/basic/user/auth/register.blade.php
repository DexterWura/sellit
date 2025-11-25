@php
$content = getContent('registration.content', true);
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
            <a href="{{ route('home') }}" class="account-logo">
                <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="image">
            </a>
        </div>
        <div class="middle w-100">
            <form action="{{ route('user.register') }}" method="POST" onsubmit="return submitUserForm();">
                @csrf
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>@lang('First Name') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <input type="text" name="firstname" autocomplete="off" class="form--control"
                                placeholder="@lang('Enter first name')" value="{{ old('firstname') }}">
                            <i class="las la-user"></i>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>@lang('Last Name') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <input type="text" name="lastname" autocomplete="off" class="form--control"
                                placeholder="@lang('Enter last name')" value="{{ old('lastname') }}">
                            <i class="las la-user"></i>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>@lang('Username') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <input type="text" name="username" autocomplete="off" class="form--control checkUser"
                                placeholder="@lang('Enter username')" value="{{ old('username') }}">
                            <i class="las la-user"></i>
                        </div>
                        <small class="text-danger usernameExist"></small>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>@lang('Email') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <input type="text" name="email" autocomplete="off" class="form--control checkUser"
                                placeholder="@lang('Enter email address')" value="{{ old('email') }}">
                            <i class="las la-envelope"></i>
                        </div>
                    </div>
                    <div class="col-sm-6 form--group">
                        <label>@lang('Country') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <select name="country" id="country" class="form--control">
                                @foreach ($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}"
                                    data-code="{{ $key }}">
                                    {{ __($country->country) }}</option>
                                @endforeach
                            </select>
                            <i class="las la-map-marked-alt"></i>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>@lang('Mobile') <sup class="text--danger">*</sup></label>
                        <div class="input-group">
                            <span class="input-group-text style--two mobile-code text-white bg-transparent"></span>
                            <input type="hidden" name="mobile_code">
                            <input type="hidden" name="country_code">
                            <input type="tel" name="mobile" id="mobile" autocomplete="off"
                                placeholder="@lang('Mobile number')" class="form--control checkUser"
                                value="{{ old('mobile') }}">
                        </div>
                        <small class="text-danger mobileExist"></small>
                    </div>

                    <div class="col-sm-6 form-group hover-input-popup">
                        <label>@lang('Password') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <input type="password" name="password" autocomplete="off" class="form--control"
                                placeholder="@lang('Enter password')">
                            <i class="las la-lock"></i>
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
                    <div class="col-sm-6 form-group">
                        <label>@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                        <div class="custom-icon-field">
                            <input type="password" name="password_confirmation" autocomplete="off" class="form--control"
                                placeholder="@lang('Confirm password')">
                            <i class="las la-lock"></i>
                        </div>
                    </div>
                    <div class="col-sm-12 form-group">
                        @php echo loadReCaptcha() @endphp
                    </div>
                    @include($activeTemplate.'partials.custom_captcha')
                    @if ($general->agree)
                    @php
                    $policyPages = getContent('policy_pages.element', false, null, true);
                    @endphp
                    <div class="col-sm-12 form-group">
                        <input type="checkbox" id="agree" name="agree">
                        <label for="agree">@lang('I agree with ')
                            @forelse ($policyPages as $page)
                            <a href="{{ route('page.details', [$page->id, slug($page->data_values->title)]) }}"
                                class="policy-link-page">
                                {{ $page->data_values->title }}{{ $loop->last ? '' : ',' }}
                            </a>
                            @empty
                            @endforelse
                        </label>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn--base w-100 mt-2">@lang('Submit')</button>
                        <p class="text-white mt-2 text-lg-start">@lang('Already you have an account? ')
                            <a href="{{ route('user.login') }}" class="text--base">@lang('Login')</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
        <div class="bottom w-100 text-center">
            @include($activeTemplate.'partials.copyright_text')
        </div>
    </div>
    <div class="right bg_img"
        style="background-image: url({{ getImage('assets/images/frontend/registration/' . $content->data_values->background_image, '1360x910') }});">

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
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">@lang('You already have an account please sign in ')</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
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
        (function($) {
            @if ($mobile_code)
                $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            @if ($general->secure_password)
                $('input[name=password]').on('input',function(){
                secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response['data'] && response['type'] == 'email') {
                        console.log('oke');
                        $('#existModalCenter').modal('show');
                    } else if (response['data'] != null) {
                        console.log('mobile');
                        $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                    } else {
                        $(`.${response['type']}Exist`).text('');
                    }
                });
            });

        })(jQuery);
</script>
@endpush
