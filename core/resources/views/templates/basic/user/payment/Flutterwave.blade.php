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
                                        {{showAmount($deposit->final_amo)}}{{__($deposit->method_currency)}}
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('To Get')</span>
                                <div class="value">
                                    <span class="charge">
                                        {{showAmount($deposit->amount)}}{{__($general->cur_text)}}
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-md btn--base w-100 mt-3 req_confirm" id="btn-confirm"
                        onClick="payWithRave()">@lang('Pay Now')</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script>
    "use strict"
        var btn = document.querySelector("#btn-confirm");
        btn.setAttribute("type", "button");
        const API_publicKey = "{{$data->API_publicKey}}";

        function payWithRave() {
            var x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: "{{$data->customer_email}}",
                amount: "{{$data->amount }}",
                customer_phone: "{{$data->customer_phone}}",
                currency: "{{$data->currency}}",
                txref: "{{$data->txref}}",
                onclose: function () {
                },
                callback: function (response) {
                    var txref = response.tx.txRef;
                    var status = response.tx.status;
                    var chargeResponse = response.tx.chargeResponseCode;
                    if (chargeResponse == "00" || chargeResponse == "0") {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    } else {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    }
                        // x.close(); // use this to close the modal immediately after payment.
                    }
                });
        }
</script>
@endpush