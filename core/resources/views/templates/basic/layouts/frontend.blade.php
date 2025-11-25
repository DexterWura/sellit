@extends($activeTemplate.'layouts.app')
@section('app')

@include($activeTemplate.'partials.header')

@if (!request()->routeIs('home'))
@include($activeTemplate .'partials.breadcrumb')
@endif

<div class="main-wrapper">
    @yield('content')
</div>

@include($activeTemplate.'partials.footer')

@endsection