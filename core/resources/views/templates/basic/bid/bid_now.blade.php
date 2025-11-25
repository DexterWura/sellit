@php
$notice = $general->notice_for_buyer;
@endphp
@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @if($notice)
                <div class="welcome-card mb-4">
                    <div class="welcome-card__content">
                        <h4 class="text-white"><i class="fas fa-exclamation-triangle text--danger me-2"></i>
                        {{ __(@$notice->heading) }}</h4>
                        <p class="mt-3 text-white">{{ __(@$notice->notice) }}</p>
                    </div>
                </div>
                @endif
                <div class="card custom--card">
                    <div class="card-body">

                        <div class="row align-items-center mt-1 justify-content-between">
                            <div class="col-md-6">
                                <h6>@lang('Domain') : {{ __($domain->name) }}</h6>
                                <h6>@lang('Price') : {{ showAmount($domain->price) }} {{ $general->cur_text }}</h6>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div id="timer">
                                    <span id="days"></span>
                                    <span id="hours"></span>
                                    <span id="minutes"></span>
                                    <span id="seconds"></span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('user.make.bid.store', $domain->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="input-group">
                                <span class="input-group-text text-white border-0">{{ $general->cur_sym }}</span>
                                <input type="number" step="any" name="offer_price" class="form--control offerPrice" placeholder="@lang('Enter Amount')">
                            </div>
                            <p class="mb-3">@lang('Minimum Bid Limit') : <b>{{ $general->cur_sym }}{{showAmount($domain->price) }}</b></p>
                            <div class="text-end">
                                <button type="submit" class="btn btn--base offerSubmit w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script>
    "use strict";
        function countdownTime() {
            var endTime = "{{ $domain->end_time }}";
            endTime = (Date.parse(endTime) / 1000);
            var now = new Date();
            now = (Date.parse(now) / 1000);
            var timeLeft = endTime - now;
            var days = Math.floor(timeLeft / 86400);
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

            if (hours < "10") { hours = "0" + hours; }
            if (minutes < "10") { minutes = "0" + minutes; }
            if (seconds < "10") { seconds = "0" + seconds; }

            $("#days").html(`<span class="text--base pe-1">${days}</span><span>Days</span>`);
            $("#hours").html(`<span class="text--base pe-1">${hours}</span><span>Hours</span>`);
            $("#minutes").html(`<span class="text--base pe-1">${minutes}</span><span>Minutes</span>`);
            $("#seconds").html(`<span class="text--base pe-1">${seconds}</span><span>Seconds</span>`);
        }
        setInterval(function() { countdownTime(); }, 1000);
</script>
@endpush
