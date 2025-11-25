@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-xl-10 col-md-9 col-sm-8 text-sm-start text-center">
                                <h6>@lang('Deposit Logs')</h6>
                            </div>
                            <div class="col-xl-2 col-md-3 col-sm-4 mt-sm-0 mt-1 text-sm-end text-center">
                                <a href="{{ route('user.deposit') }}" class="btn btn-sm btn--base">
                                    <i class="las la-wallet"></i> @lang('Deposit Now')
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Transaction ID')</th>
                                        <th>@lang('Gateway')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Time')</th>
                                        <th>@lang('MORE')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($logs) >0)
                                    @foreach($logs as $k=>$data)
                                    <tr>
                                        <td data-label="@lang('Transaction ID')">{{$data->trx}}</td>
                                        <td data-label="@lang('Gateway')">{{ __(@$data->gateway->name) }}</td>
                                        <td data-label="@lang('Amount')">{{showAmount($data->amount)}}
                                            {{__($general->cur_text)}}
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if($data->status == 1)
                                            <span class="badge badge--success">@lang('Complete')</span>
                                            @elseif($data->status == 2)
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($data->status == 3)
                                            <span class="badge badge--danger">@lang('Cancel')</span>
                                            @endif

                                            @if($data->admin_feedback != null)
                                            <button class="btn-info btn-rounded  badge detailBtn"
                                                data-admin_feedback="{{$data->admin_feedback}}"><i
                                                    class="fa fa-info"></i></button>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Time')">{{showDateTime($data->created_at)}}</td>
                                        @php
                                        $details = ($data->detail != null) ? json_encode($data->detail) : null;
                                        @endphp
                                        <td data-label="@lang('Details')">
                                            <button class="icon-btn btn--base approveBtn" data-info="{{ $details }}"
                                                data-id="{{ $data->id }}"
                                                data-amount="{{ showAmount($data->amount)}} {{ __($general->cur_text) }}"
                                                data-charge="{{ showAmount($data->charge)}} {{ __($general->cur_text) }}"
                                                data-after_charge="{{ showAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}"
                                                data-rate="{{ showAmount($data->rate)}} {{ __($data->method_currency) }}"
                                                data-payable="{{ showAmount($data->final_amo)}} {{ __($data->method_currency) }}">
                                                <i class="las la-desktop" data-bs-toggle="tooltip"
                                                    data-bs-position="top" title="View Details"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{$logs->links()}}
            </div>
        </div>
    </div>
</div>

{{-- APPROVE MODAL --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">@lang('Deposit Details')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <ul class="caption-list">
                    <li>
                        <span class="caption">@lang('Amount')</span>
                        <span class="withdraw-amount value"></span>
                    </li>
                    <li>
                        <span class="caption">@lang('Charge')</span>
                        <span class="withdraw-charge  value"></span>
                    </li>
                    <li>
                        <span class="caption">@lang('After Charge')</span>
                        <span class="withdraw-after_charge value"></span>
                    </li>
                    <li>
                        <span class="caption">@lang('Conversion Rate')</span>
                        <span class="withdraw-rate value"></span>
                    </li>
                    <li>
                        <span class="caption">@lang('Payable Amount')</span>
                        <span class="withdraw-payable value"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Detail MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="withdraw-detail"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    (function ($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('payable'));
                modal.modal('show');
            });

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);
</script>
@endpush
