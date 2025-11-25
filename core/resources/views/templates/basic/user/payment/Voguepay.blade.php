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
                    <button type="submit" class="btn btn-md btn--base w-100 mt-3" id="btn-confirm">@lang('Pay
                        Now')</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="//pay.voguepay.com/js/voguepay.js"></script>
<script>
    "use strict";
        var closedFunction = function() {
        }
        var successFunction = function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}';
        }
        var failedFunction=function(transaction_id) {
            window.location.href = '{{ route(gatewayRedirectUrl()) }}' ;
        }

        function pay(item, price) {
            //Initiate voguepay inline payment
            Voguepay.init({
                v_merchant_id: "{{ $data->v_merchant_id}}",
                total: price,
                notify_url: "{{ $data->notify_url }}",
                cur: "{{$data->cur}}",
                merchant_ref: "{{ $data->merchant_ref }}",
                memo:"{{$data->memo}}",
                recurrent: true,
                frequency: 10,
                developer_code: '60a4ecd9bbc77',
                custom: "{{ $data->custom }}",
                customer: {
                  name: 'Customer name',
                  country: 'Country',
                  address: 'Customer address',
                  city: 'Customer city',
                  state: 'Customer state',
                  zipcode: 'Customer zip/post code',
                  email: 'example@example.com',
                  phone: 'Customer phone'
                },
                closed:closedFunction,
                success:successFunction,
                failed:failedFunction
            });
        }

        (function ($) {
            
            $('#btn-confirm').on('click', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });

        })(jQuery);
</script>
@endpush