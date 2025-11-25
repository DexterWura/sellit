@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            @if(Auth::user()->ts)
            <div class="col-xl-8 col-lg-10 mt-4">
                <div class="card custom--card section--bg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <div class="qr-code-wrapper rounded-2">
                                    <img src="{{$qrCodeUrl}}" alt="image" class="w-100">
                                    <p class="font-size--14px text-center mt-3 fs--14px">@lang('Use Google
                                        Authentication App to scan the QR code. ')<a
                                            href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                            class="text--base">@lang('App link')</a></p>
                                </div>
                            </div>
                            <div class="col-lg-8 ps-lg-5 mt-lg-0 mt-5">
                                <div class="qr-code-content">
                                    <h4>@lang('Your 2FA Verification is Enabled.')</h4>
                                    <p class="mt-4 text--dark mb-1">@lang('Disable 2FA Security')</p>
                                    <form class="qr-code-form" action="{{route('user.twofactor.disable')}}"
                                        method="POST">
                                        @csrf
                                        <input type="text" name="code" class="form--control rounded-2"
                                            placeholder="@lang('Enter Google Security Code')">
                                        <button type="submit" class="qr-code-form__btn text-white rounded-2">@lang('Apply')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-xl-8 col-lg-10">
                <div class="card custom--card section--bg">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <div class="qr-code-wrapper rounded-2">
                                    <img src="{{$qrCodeUrl}}" alt="image" class="w-100">
                                    <p class="font-size--14px text-center mt-3 fs--14px">@lang('Use Google
                                        Authentication App to scan the QR code. ')
                                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                                            class="text--base">@lang('App link')</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-8 ps-lg-5 mt-lg-0 mt-5">
                                <div class="qr-code-content">
                                    <div class="qr-code text--base mb-1">
                                        <form class="qr-code-copy-form" data-copy=true>
                                            <input type="text" value="{{ $secret }}" id="qr-code-text">
                                            <button type="submit" class="text-copy-btn text-white copy-btn"
                                                data-bs-toggle="tooltip"
                                                data-bs-original-title="Copy to clipboard">@lang('Copy')
                                            </button>
                                        </form>
                                    </div>
                                    <small><i class="fas fa-info-circle"></i>@lang('If you have any problem with
                                        scanning the QR code enter this code manually into the APP.')</small>
                                    <p class="mt-4 text--dark mb-1">@lang('Enable 2FA Security')</p>
                                    <form class="qr-code-form" action="{{route('user.twofactor.enable')}}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="key" value="{{$secret}}">
                                        <input type="text" name="code" class="form--control rounded-2"
                                            placeholder="@lang('Enter the QR code')">
                                        <button type="submit"
                                            class="qr-code-form__btn text-white rounded-2">@lang('Apply')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    (function($){
            "use strict";

            $('.copytext').on('click',function(){
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
            });

            $('.copy-btn').on('click',function(e){
                e.preventDefault();
                var copyText = document.getElementById("qr-code-text");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");

                $(this).tooltip('show')
                    .attr('data-bs-original-title', 'Copied')
                    .tooltip('show');
                });

                $('.copy-btn').on('mouseout',function(e){
                $(this).attr('data-bs-original-title', 'Copy to clipboard');
            });
        })(jQuery);
</script>
@endpush
