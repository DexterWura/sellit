@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <form action="{{route('user.withdraw.money')}}" method="post">
            @csrf
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-6">
                    <div class="add-money-card">
                        <h4 class="title"><i class="las la-money-bill-wave-alt"></i> @lang('Withdraw Money')</h4>
                        <div class="form-group">
                            <label>@lang('Select Withdraw Method')</label>
                            <select class="select withdrawMethod">
                                <option value="" selected disabled>@lang('Please select one')</option>
                                @foreach($withdrawMethod as $data)
                                <option value="{{ $data->id }}" data-id="{{$data->id}}" data-resource="{{$data}}"
                                    data-min_amount="{{showAmount($data->min_limit)}}"
                                    data-max_amount="{{showAmount($data->max_limit)}}"
                                    data-fix_charge="{{showAmount($data->fixed_charge)}}"
                                    data-percent_charge="{{showAmount($data->percent_charge)}}"
                                    data-base_symbol="{{__($general->cur_text)}}" data-delay="{{__($data->delay)}}">
                                    {{__($data->name)}}&nbsp;@lang('-')&nbsp;{{__($data->currency)}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>@lang('Amount')</label>
                            <input type="hidden" name="currency" class="edit-currency form--control">
                            <input type="hidden" name="method_code" class="edit-method-code  form--control">
                            <div class="input-group">
                                <input class="form--control withdawAmount" id="amount" type="number" step="any"
                                    onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" name="amount"
                                    placeholder="0.00" required="" value="{{old('amount')}}" autofocus="off">
                                <span class="input-group-text bg--base text-white">{{__($general->cur_text)}}</span>
                            </div>
                            <code class="text--danger methodError"></code>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="add-money-card style--two">
                        <h4 class="title"><i class="lar la-file-alt"></i> @lang('Summary')</h4>
                        <div class="add-moeny-card-middle">
                            <ul class="add-money-details-list">
                                <li>
                                    <span class="caption">@lang('Limit')</span>
                                    <div class="value"> <span class="show-amount limit">@lang('0.00')</span></div>
                                </li>
                                <li>
                                    <span class="caption">@lang('Charge')</span>
                                    <div class="value">
                                        <span class="charge withdrawCharge">
                                            @lang('0.00') {{ $general->cur_text }} @lang('+ 0 %')</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="add-money-details-bottom">
                                <span class="caption">@lang('Processing Time')</span>
                                <div class="value"><span class="payable delay">@lang('---')</span> </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-md btn--base w-100 mt-3 req_confirm">@lang('Proceed')</button>
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
            $('.withdrawMethod').change(function (e) {
                e.preventDefault();
                var currency = $(this).find(':selected').data('currency');
                var resource = $(this).find(':selected').data('resource');
                var minAmount = $(this).find(':selected').data('min_amount');
                var maxAmount = $(this).find(':selected').data('max_amount');
                var fixCharge = $(this).find(':selected').data('fix_charge');
                var percentCharge = $(this).find(':selected').data('percent_charge');
                var delay = $(this).find(':selected').data('delay');
                var withdrawLimit = `${minAmount} - ${maxAmount}  {{__($general->cur_text)}}`;
                $('.limit').text(withdrawLimit)
                var withdrawCharge = `${fixCharge} {{__($general->cur_text)}} ${(0 < percentCharge) ? ' + ' + percentCharge + ' %' : ''}`
                $('.withdrawCharge').text(withdrawCharge);
                $('.delay').text(delay);
                $('.edit-currency').val(resource.currency);
                $('.edit-method-code').val(resource.id);

            });
            $('.withdawAmount').on('input', function () {
                var fixCharge = $('.withdrawMethod').find(':selected').data('fix_charge');
                if(fixCharge == undefined){
                    var message = `@lang('Select Withdraw Method')`
                    $('.methodError').html(message);
                }
            });
        })(jQuery);
</script>

@endpush
