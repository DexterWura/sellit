@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-100 pb-100 section--bg">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="card custom--card top--border-base">
                <div class="card-body">
                    <div class="card-wrapper"></div>
                    <form role="form" id="payment-form" method="{{$data->method}}" action="{{$data->url}}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>@lang('Name on card')</label>
                                <div class="input-group">
                                    <input type="text" class="form--control" name="name" placeholder="@lang('Name on Card')" autocomplete="off" autofocus/>
                                    <span class="input-group-text border-0 text-white"><i class="fa fa-font"></i></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>@lang('Card Number')</label>
                                <div class="input-group">
                                    <input type="tel" class="form--control" name="cardNumber" placeholder="@lang('Valid Card Number')" autocomplete="off" required autofocus/>
                                    <span class="input-group-text border-0 text-white"><i class="fa fa-credit-card"></i></span>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>@lang('Expiration Date')</label>
                                <input type="tel" class="form--control" name="cardExpiry" placeholder="@lang('MM / YYYY')" autocomplete="off" required/>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <label>@lang('CVC Code')</label>
                                <input type="tel" class="form--control" name="cardCVC" placeholder="@lang('CVC')" autocomplete="off" required/>
                            </div>
                            <div class="form-group mb-0">
                            <input type="submit" class="mt-2 btn btn--base w-100 text-center" value="@lang('Pay Now')">
                            </div>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function ($) {
            "use strict";
            var card = new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });
        })(jQuery);
    </script>
@endpush
