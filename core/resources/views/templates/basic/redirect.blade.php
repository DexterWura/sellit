<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link rel="icon" type="image/png"
        href="{{ getImage(imagePath()['logoIcon']['path'] . '/favicon.png', '?' . time()) }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">

    <style>
        .link-redirect-logo {
            width: 230px;
        }

        .link-redirect-logo a {
            display: block;
        }

        .link-redirect-logo a img {
            width: 100%;
            height: 60px;
            object-fit: contain;
            object-position: left;
        }

        @media (max-width: 575px) {
            .link-redirect-logo {
                width: 150px;
            }
        }

        .link-redirect-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: radial-gradient(circle, rgba(6, 22, 58, 0.8) -5%, #06163a 40%);
            padding: 15px 0;
        }

        .link-redirect-header .link-redirect-logo,
        .link-redirect-header .skip-it {
            margin: 0 15px;
        }

        .link-redirect-header .skip-it a {
            display: block;
        }

        .link-redirect-header .skip-it a img {
            width: 100%;
        }

        .skip-btn {
            font-size: 18px;
            font-weight: 500;
            font-family: "Exo 2", sans-serif;
            text-transform: capitalize;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 20px 8px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            position: relative;
            display: flex;
            align-items: center;
            color: #ffffffff;
        }

        .skip-btn:hover {
            color: #ffffffff;
        }

        .skip-btn i {
            position: relative;
            top: 1px;
            font-size: 16px;
        }

        @media (max-width: 575px) {
            .skip-btn {
                font-size: 14px;
                padding-left: 10px;
                padding-right: 10px;
                padding-top: 5px;
                padding-bottom: 5px;
            }
        }

        .link-header-bottom {
            padding-left: 15px;
            padding-right: 15px;
        }

        .no-skip {
            position: absolute;
            inset: 0;
            text-align: center;
            line-height: 41px;
            background: rgba(234, 84, 85, 1);
            font-size: 20px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            color: #ffffffff;
        }

        @media (max-width: 575px) {
            .no-skip {
                line-height: 30px;
                font-size: 14px;
            }
        }

        .skip-it {
            position: relative;
        }
    </style>

</head>
<body>

<!-- Header Section -->
<div class="link-header">
    <div class="link-redirect-header">
        <div class="link-redirect-logo">
            <a href="{{url('/')}}">
                <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png', '?' . time()) }}" alt="logo">
            </a>
        </div>
        <div class="skip-it">
            <a href="" class="skip-btn">
                @lang('Skip Ads') <i class="las la-forward"></i>
            </a>

            <span class="no-skip">
                @lang('Skip in') {{$ad->time??$general->ad_duration}}s
            </span>
        </div>
    </div>
</div>

<!-- Header Section -->
@isset($ad->url)
<iframe class="redirect-iframe" width="100%" src="{{$ad->url}}" frameborder="0"></iframe>
@else
@php
    echo $ad->script??'';
@endphp
@endisset




<script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

<script>
    'use strict';
        (function ($) {

        window.addEventListener('load', () => {
            const skipAdd = document.querySelector('.no-skip')

            var duration = '{{$ad->time??$general->ad_duration}}'

            var n = parseInt(duration);

            for(var i = n; i >= 0; i--) {
                (function(i){
                    setTimeout(() => {
                        skipAdd.textContent = `Skip in ${Math.abs(-n+i)}s`
                    },  i*1000)
                })(i)
            }

            (function(){
                setTimeout(()=>{
                    $('.skip-btn').attr('href','http://{{ $redirectUrl }}')
                    skipAdd.remove()
                }, n*1000+200)
            })()

        });

        var headerHeight = $('.link-header').height();

        $('.redirect-iframe').css('height', function(){
            return `calc(100vh - ${headerHeight}px)`
        })

        })(jQuery);
</script>
</body>

</html>
