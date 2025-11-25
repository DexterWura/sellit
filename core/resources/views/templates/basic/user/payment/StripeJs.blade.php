@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row gy-4 justify-content-center">
            <div class="col-xl-4 col-lg-6">
                <div class="add-money-card">
                    <h4 class="title"><i class="las la-wallet"></i> @lang('Payment Preview')</h4>
                    <div class="form-group">
                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="img-fluid" alt="@lang('Image')"
                            class="w-50">
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6">
                <div class="add-money-card style--two">
                    <h4 class="title"><i class="lar la-file-alt"></i> @lang('Summery')</h4>
                    <div class="add-moeny-card-middle">
                        <ul class="add-money-details-list">
                            <li>
                                <span class="caption">@lang('Please Pay')</span>
                                <div class="value">
                                    <span class="show-amount">
                                        {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('To Get')</span>
                                <div class="value">
                                    <span class="charge">
                                        {{showAmount($deposit->amount)}}&nbsp;{{__($general->cur_text)}}
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <form action="{{$data->url}}" method="{{$data->method}}">
                        <script src="{{$data->src}}" class="stripe-button" 
                            @foreach($data->val as $key=> $value)
                                data-{{$key}}="{{$value}}"
                            @endforeach>
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    (function ($) {
            "use strict";
            $('button[type="submit"]').addClass("btn btn-md btn--base w-100 mt-3");
        })(jQuery);
</script>
@endpush