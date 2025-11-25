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
                                <h6>@lang('Withdrawal Logs')</h6>
                            </div>
                            <div class="col-xl-2 col-md-3 col-sm-4 mt-sm-0 mt-1 text-sm-end text-center">
                                <a href="{{ route('user.withdraw') }}" class="btn btn-sm btn--base">
                                    <i class="las la-money-bill"></i> @lang('Withdraw Now')
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--lg">
                            <table class="table custom--table mb-0">
                                <thead>
                                    <tr>
                                        <th>@lang('Transaction ID')</th>
                                        <th>@lang('Gateway')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Charge')</th>
                                        <th>@lang('After Charge')</th>
                                        <th>@lang('Rate')</th>
                                        <th>@lang('Receivable')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Time')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdraws as $k=>$data)
                                    <tr>
                                        <td data-label="@lang('Transaction ID')">{{$data->trx}}</td>
                                        <td data-label="@lang('Gateway')">{{ __($data->method->name) }}</td>
                                        <td data-label="@lang('Amount')">{{showAmount($data->amount)}}
                                            {{__($general->cur_text)}}
                                        </td>
                                        <td data-label="@lang('Charge')">
                                            {{showAmount($data->charge)}} {{__($general->cur_text)}}
                                        </td>
                                        <td data-label="@lang('After Charge')">
                                            {{showAmount($data->after_charge)}} {{__($general->cur_text)}}
                                        </td>
                                        <td data-label="@lang('Rate')">
                                            {{showAmount($data->rate)}} {{__($data->currency)}}
                                        </td>
                                        <td data-label="@lang('Receivable')">
                                            {{showAmount($data->final_amount)}} {{__($data->currency)}}
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if($data->status == 2)
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($data->status == 1)
                                            <span class="badge badge--success">@lang('Completed')</span>
                                            <button class="btn-info btn-rounded  badge approveBtn"
                                                data-admin_feedback="{{$data->admin_feedback}}"><i
                                                    class="fa fa-info"></i></button>
                                            @elseif($data->status == 3)
                                            <span class="badge badge-danger">@lang('Rejected')</span>
                                            <button class="btn-info btn-rounded badge approveBtn"
                                                data-admin_feedback="{{$data->admin_feedback}}"><i
                                                    class="fa fa-info"></i></button>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Time')">{{showDateTime($data->created_at)}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{$withdraws->links()}}
            </div>
        </div>
    </div>
</div>

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
    (function($){
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);

</script>
@endpush
