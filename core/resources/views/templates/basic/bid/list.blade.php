@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Ends')</th>
                                        <th>@lang('Win Status')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($DomainBid as $bid)
                                    <tr>
                                        <td data-label="@lang('Name')">
                                            <h6 class="title fs--16px"><a href="{{ route('domain.detail',['id'=>@$bid->domain->id,'name'=>slug(@$bid->domain->name)]) }}">{{ @$bid->domain->name }}</a></h6>
                                        </td>
                                        <td data-label="@lang('Price')">
                                            {{showAmount(@$bid->domain->price) }}&nbsp;{{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('Ends')">
                                            <span data-countdown="{{ @$bid->domain->end_time }}" data-title="@lang('This auction ended')"></span>
                                        </td>
                                        <td data-label="@lang('Win Status')">
                                            @if ($bid->win_status == 1)
                                            <span class="badge badge--success">@lang('Win')</span>
                                            @elseif($bid->win_status == 2)
                                            <span class="badge badge--danger">@lang('Lose')</span>
                                            @else
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Status')">
                                            @php
                                                echo $bid->statusText
                                            @endphp
                                        </td>


                                        <td data-label="@lang('Action')">
                                            <button class="icon-btn bg--base approveBtn"
                                                data-offer_price="{{ showAmount($bid->price) }} {{ $general->cur_text }}"
                                                data-domain_name="{{@$bid->domain->name}}"
                                                data-domain_price="{{showAmount(@$bid->domain->price)}} {{ __($general->cur_text) }}"
                                                data-end_time="{{ $bid->domain->end_time }}">
                                                <i class="las la-desktop" data-bs-toggle="tooltip"
                                                    data-bs-position="top" title="View Details"></i>
                                            </button>
                                            @if ($bid->win_status == 1)
                                            <a href="{{ route('user.bid.conversation',['id'=>$bid->id,'domain_id'=>$bid->domain->id]) }}"
                                                class="icon-btn btn--info">
                                                <i class="las la-sms" data-bs-toggle="tooltip"
                                                    data-bs-position="top" title="Send Message"></i>
                                            </a>
                                            @endif

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan=" 100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- custom--card end -->
                {{ $DomainBid->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">@lang('Bid Details')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <ul class="caption-list">
                    <li>
                        <span class="caption">@lang('Domain Name')</span>
                        <span class="domain_name value"></span>
                    </li>
                    <li>
                        <span class="caption">@lang('Domain Price')</span>
                        <span class="domain_price value"></span>
                    </li>
                    <li>
                        <span class="caption">@lang('Bid Offer')</span>
                        <span class="bid_price value"></span>
                    </li>

                    <li>
                        <span class="caption">@lang('Auction End')</span>
                        <span class="auction_end value"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
@push('style')
<style>
    .has-notification {
        position: relative;
    }

    .has-notification::after {
        position: absolute;
        content: '';
        top: -24px;
        right: -12px;
        width: 13px;
        height: 13px;
        border: 2px solid #fff;
        background-color: red;
        border-radius: 50%;
    }

</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.approveBtn').on('click', function() {
            var modal = $('#approveModal');
            modal.find('.domain_name').text($(this).data('domain_name'));
            modal.find('.domain_price').text($(this).data('domain_price'));
            modal.find('.bid_price').text($(this).data('offer_price'));
            modal.find('.auction_end').text($(this).data('end_time'));
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
