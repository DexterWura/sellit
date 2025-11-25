<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link rel="icon" type="image/png" href="{{ getImage(imagePath()['logoIcon']['path'] . '/favicon.png') }}"
        sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $general->base_color }}">

    @stack('style-lib')

    @stack('style')
</head>

<body>
    @stack('fbComment')

    @include($activeTemplate.'partials.preloader_scroll')

    @yield('app')

    @php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
    @endphp
    @if(@$cookie->data_values->status && !session('cookie_accepted'))
    <div class="cookie__wrapper">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <span class="txt my-2">
                    @php echo @$cookie->data_values->description @endphp
                    <a href="{{ @$cookie->data_values->link }}" target="_blank" class="text--base">
                        @lang('Read Policy')
                    </a>
                </span>
                <button class="btn btn--base my-2 acceptPolicy">@lang('Accept')</button>
            </div>
        </div>
    </div>
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery-ui.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.countdown.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/app.js') }}"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')

    @include('partials.notify')

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });
            $('.acceptPolicy').on('click',function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.get('{{route('cookie.accept')}}', function(response){
                    $('.cookie__wrapper').addClass('d-none');
                });
            });
        })(jQuery);
    </script>

</body>

</html>
