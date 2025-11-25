@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
  <div class="container">
    <form action="{{route('user.deposit.insert')}}" method="post">
      @csrf
      <div class="row gy-4 justify-content-center">
        <div class="col-lg-6">
          <div class="add-money-card">
            <div class="form-group">
              <label>@lang('Select Payment Method')</label>
              <select class="select depositmethod">
                <option value="" selected disabled>@lang('Please select one')</option>
                @foreach ($gatewayCurrency as $data)
                <option value="{{ $data->id }}" data-id="{{$data->id}}" data-name="{{$data->name}}"
                  data-currency="{{$data->currency}}" data-method_code="{{$data->method_code}}"
                  data-min_amount="{{showAmount($data->min_amount)}}"
                  data-max_amount="{{showAmount($data->max_amount)}}" data-base_symbol="{{$data->baseSymbol()}}"
                  data-fix_charge="{{showAmount($data->fixed_charge)}}"
                  data-percent_charge="{{showAmount($data->percent_charge)}}">{{__($data->name)}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-0">
              <label>@lang('Amount')</label>
              <input type="hidden" name="currency" class="edit-currency">
              <input type="hidden" name="method_code" class="edit-method-code">
              <div class="input-group">
                <input class="form--control depositAmount" id="amount" type="number" step="any" autocomplete="off"
                  name="amount" placeholder="@lang('Enter amount')" required value="{{old('amount')}}">
                <span class="input-group-text bg--base text-white">{{__($general->cur_text)}}</span>
              </div>
              <code class="text--dark depositLimit"></code>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="add-money-card style--two">
            <h4 class="title"><i class="lar la-file-alt"></i> @lang('summary')</h4>
            <div class="add-moeny-card-middle">
              <ul class="add-money-details-list">
                <li>
                  <span class="caption">@lang('Amount')</span>
                  <div class="value"> <span class="show-amount">{{ $general->cur_sym }}@lang('0.00')</span></div>
                </li>
                <li>
                  <span class="caption">@lang('Charge')</span>
                  <div class="value"> <span class="charge">{{ $general->cur_sym }}@lang('0.00')</span> </div>
                </li>
              </ul>
              <div class="add-money-details-bottom">
                <span class="caption">@lang('Payable')</span>
                <div class="value"><span class="payable">{{ $general->cur_sym }}@lang('0.00')</span> </div>
              </div>
            </div>
            <button type="submit" class="btn btn-md btn--base w-100 mt-3 req_confirm">@lang('Proceed')</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection
@push('script')
<script>
  (function ($) {
            "use strict";
            $('.depositmethod').change(function (e) {
                e.preventDefault();
                var id = $(this).find(':selected').data('id');
                var name = $(this).find(':selected').data('name');
                var currency = $(this).find(':selected').data('currency');
                var method_code = $(this).find(':selected').data('method_code');
                var minAmount = $(this).find(':selected').data('min_amount');
                var maxAmount = $(this).find(':selected').data('max_amount');
                var baseSymbol = "{{$general->cur_text}}";
                var fixCharge = $(this).find(':selected').data('fix_charge');
                var percentCharge = $(this).find(':selected').data('percent_charge');
                var depositLimit = `<span class="text--danger">@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}</span>`;
                $('.depositLimit').html('<i class="fas fa-info-circle"></i>'+' '+depositLimit);
                $('.edit-currency').val(currency);
                $('.edit-method-code').val(method_code);
                var amount = parseFloat($('.depositAmount').val());
                if(isNaN(amount)){
                  amount = 0;
                }
                var charge = parseFloat(fixCharge) + (amount * parseFloat(percentCharge) / 100);
                $('.show-amount').text("{{ $general->cur_sym }}"+amount)
                $('.charge').text("{{ $general->cur_sym }}"+charge)
                $('.payable').text("{{ $general->cur_sym }}"+(amount+charge))
            });

            $('.depositAmount').on('input', function () {
                var fixCharge = $('.depositmethod').find(':selected').data('fix_charge');
                var percentCharge = $('.depositmethod').find(':selected').data('percent_charge');
                if(fixCharge == undefined){
                    var message = `<p class="text-danger">@lang('Select Payment Method')</p>`
                    $('.depositLimit').html(message);
                }
                let amount = parseFloat($(this).val());
                var charge = parseFloat(fixCharge) + (amount * parseFloat(percentCharge) / 100);
                if(isNaN(charge)){
                  charge = 0;
                }
                $('.show-amount').text("{{ $general->cur_sym }}"+amount)
                $('.charge').text("{{ $general->cur_sym }}"+charge)
                $('.payable').text("{{ $general->cur_sym }}"+(amount+charge))

            });
            $('.prevent-double-click').on('click',function(){
                $(this).addClass('button-none');
                $(this).html('<i class="fas fa-spinner fa-spin"></i> @lang('Processing')...');
            });
        })(jQuery);
</script>
@endpush


@push('style')
<style type="text/css">

</style>
@endpush
