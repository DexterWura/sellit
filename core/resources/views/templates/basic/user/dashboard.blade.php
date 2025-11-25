@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 pe-4">
                <div class="user-widget">
                    <div class="user-widget__top-shape"></div>
                    <div class="user-details mb-4">
                        <div class="shape-1"></div>
                        <div class="shape-2"></div>
                        <div class="thumb">
                            <img src="{{ getImage(imagePath()['profile']['user']['path'] . '/' . auth()->user()->image,null,true) }}"
                                alt="image">
                        </div>
                        <div class="content">
                            <h5 class="text-white">{{ __(Auth::user()->username) }}</h5>
                            <span class="text-white fs--14px"><i class="fas fa-map-marker-alt me-1 text--base"></i>
                                {{ __(Auth::user()->address->country) }}</span>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-lg-12 col-md-4">
                            <div class="d-widget">
                                <div class="d-widget__icon">{{ $general->cur_sym }}</div>
                                <div class="d-widget__content"> <span class="caption">@lang('BALANCE')</span>
                                    <h4 class="amount">{{ showAmount(Auth::user()->balance )}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4">
                            <div class="d-widget">
                                <a href="{{ route('user.auction.list') }}" class="d-widget__btn">
                                    @lang('View All')
                                </a>
                                <div class="d-widget__icon"> <i class="las la-clipboard-list"></i></div>
                                <div class="d-widget__content"> <span class="caption">@lang('AUCTION DOMAINS')</span>
                                    <h4 class="amount">{{ $totalDomain }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4">
                            <div class="d-widget">
                                <a href="{{ route('user.bids.history') }}" class="d-widget__btn">
                                    @lang('View All')
                                </a>
                                <div class="d-widget__icon"> <i class="las la-gavel"></i></div>
                                <div class="d-widget__content">
                                    <span class="caption">@lang('TOTAL BIDS')</span>
                                    <h4 class="amount">{{ $totalBid }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('user.auction.create') }}" class="btn btn--base mt-2 w-100">@lang('Domain Sell')</a>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="custom--card">
                    <div class="card-header border-bottom-0">
                        <h6><i class="las la-clipboard-list"></i> @lang('Your Domain List')</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Domain')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Total Bids')</th>
                                        <th>@lang('End Date')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($userDomain as $item)
                                    <tr>
                                        <td data-label="@lang('Domain')">
                                            <div class="table-website">
                                                <div class="table-website__content">
                                                    <h6 class="title fs--16px">
                                                        <a href="{{ route('domain.detail',['id'=>$item->id,'name'=>slug($item->name)]) }}" class="text--base">
                                                            {{ __($item->name) }}
                                                        </a>

                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Price')">
                                            {{ showAmount($item->price) }} {{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('Total Bids')">{{ count($item->bids) }}</td>
                                        <td data-label="@lang('End Date')">
                                            <span data-countdown="{{ $item->end_time }}"
                                                data-title="@lang('The auction end')">
                                            </span>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @php echo $item->statusText @endphp
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('user.bid.details',$item->id) }}"
                                                class="icon-btn bg--warning">
                                                <i class="fas fa-gavel" data-bs-toggle="tooltip" data-bs-position="top"
                                                    title="View Bids"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan=" 100%" class="text-center">{{ __($emptyMessage) }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="custom--card mt-3">
                    <div class="card-header border-bottom-0">
                        <h6><i class="las la-clipboard-list"></i> @lang('Bid Domain List')</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Ends')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($DomainBid as $item)
                                    <tr>
                                        <td data-label="@lang('Name')">
                                            <h6 class="title fs--16px"><a href="{{ route('domain.detail',['id'=>@$item->domain->id,'name'=>slug(@$item->domain->name)]) }}">{{ @$item->domain->name }}</a></h6>
                                        </td>
                                        <td data-label="@lang('Price')">
                                            {{showAmount(@$item->domain->price) }}&nbsp;{{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('Ends')"> <span
                                                data-countdown="{{ @$item->domain->end_time }}"
                                                data-title="@lang('This auction ended')"></span>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if ($item->win_status == 1)
                                            <span class="badge badge--success">@lang('Win')</span>
                                            @elseif($item->win_status == 2)
                                            <span class="badge badge--danger">@lang('Lose')</span>
                                            @else
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <button class="icon-btn bg--base detailBtn"
                                                data-offer_price="{{ showAmount($item->price) }} {{ $general->cur_text }}"
                                                data-domain_name="{{@$item->domain->name}}"
                                                data-domain_price="{{showAmount(@$item->domain->price)}} {{ __($general->cur_text) }}"
                                                data-end_time="{{ $item->domain->end_time }}">
                                                <i class="las la-desktop" data-bs-toggle="tooltip"
                                                    data-bs-position="top" title="View Details"></i>
                                            </button>
                                            @if ($item->win_status == 1)
                                            <a href="{{ route('user.bid.conversation',['id'=>$item->id,'domain_id'=>$item->domain_id]) }}"
                                                class="icon-btn btn--info">
                                                <i class="las la-sms"></i>
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
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
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


@push('script')
<script>
    (function ($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                modal.find('.domain_name').text($(this).data('domain_name'));
                modal.find('.domain_price').text($(this).data('domain_price'));
                modal.find('.bid_price').text($(this).data('offer_price'));
                modal.find('.auction_end').text($(this).data('end_time'));
                modal.modal('show');
            });
        })(jQuery);
</script>
@endpush
