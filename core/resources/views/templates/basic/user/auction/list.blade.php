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
                                        <th>@lang('Domain')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Total Bids')</th>
                                        <th>@lang('End Date')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($domains as $domain)
                                    <tr>
                                        <td data-label="@lang('Domain')">
                                            <div class="table-website">
                                                <div class="table-website__content">
                                                    <h6 class="title fs--16px">
                                                        <a href="{{ route('domain.detail',['id'=>$domain->id,'name'=>slug($domain->name)]) }}" class="text--base">
                                                            {{ __($domain->name) }}
                                                        </a>


                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Price')">
                                            {{ showAmount($domain->price) }} {{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('Total Bids')">{{ $domain->bids_count }}</td>
                                        <td data-label="@lang('End Date')">
                                            <span data-countdown="{{ $domain->end_time }}"
                                                data-title="@lang('The auction end')">
                                            </span>
                                        </td>

                                        <td data-label="@lang('Status')">

                                            @php
                                            echo $domain->statusText
                                            @endphp

                                            @if($domain->status == 0 && $domain->verify_code)
                                            <button class="btn-info btn-rounded badge verifyStatus"
                                                data-verify_code="{{$domain->verify_code}}"
                                                data-verify_detail="{{$domain->verify_detail}}"><i
                                                    class="fa fa-info"></i>
                                                @endif

                                                @if($domain->admin_feedback)
                                                <button class="btn-info btn-rounded badge feedbackBtn"
                                                    data-admin_feedback="{{$domain->admin_feedback}}">
                                                    <i class="fa fa-info"></i>
                                                </button>
                                                @endif
                                            </button>

                                        </td>

                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('user.auction.edit',$domain->id) }}" class="icon-btn btn--success">
                                                <i class="las la-edit" data-bs-toggle="tooltip" data-bs-position="top"
                                                    title="@lang('Edit')"></i>
                                            </a>

                                            @if($domain->status == 0 && $domain->verify_code != null)
                                            <a href="{{ route('user.auction.file.download',$domain->id) }}"
                                                class="icon-btn btn--dark" data-bs-toggle="tooltip"
                                                data-bs-position="top" title="@lang('Download')">
                                                <i class="las la-download"></i>
                                            </a>
                                            @else
                                            <a class="icon-btn btn--dark" disabled="disabled">
                                                <i class="las la-download"></i>
                                            </a>
                                            @endif

                                            <a href="{{ route('user.contact.seller.owner.message',$domain->id) }}"
                                                class="icon-btn btn--primary" @if($domain->status != 1)
                                                disabled="disabled"
                                                @endif>
                                                <i class="las la-comment" data-bs-toggle="tooltip"
                                                    data-bs-position="top" title="@lang('View Message')">
                                                </i>
                                                @if($domain->contact_messages_count > 0)
                                                    <span class="has-notification"></span>
                                                @endif
                                            </a>

                                            <a href="{{ route('user.bid.details',$domain->id) }}"
                                                class="icon-btn bg--warning">
                                                <i class="fas fa-gavel" data-bs-toggle="tooltip" data-bs-position="top"
                                                    title="@lang('View Bids')"></i>
                                                @if($domain->bids_count > 0)
                                                    <span class="has-notification"></span>
                                                @endif
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
            </div>
            {{ $domains->links() }}
        </div>
    </div>
</div>

<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="auction-detail"></div>
            </div>
        </div>
    </div>
</div>
<div id="verifyModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="verify-code"></div>
                <div class="verify-detail"></div>
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
        top: -17px;
        right: -8px;
        width: 13px;
        height: 13px;
        border: 2px solid #fff;
        background-color: red;
        border-radius: 50%;
    }

    a[disabled="disabled"] {
        pointer-events: none;
        opacity: .5;
    }
</style>
@endpush
@push('script')
<script>
    "use script";
    $('.feedbackBtn').on('click', function() {
        var modal = $('#detailModal');
        var feedback = $(this).data('admin_feedback');
        modal.find('.auction-detail').html(`<p> ${feedback} </p>`);
        modal.modal('show');
    });
    $('.verifyStatus').on('click', function() {
        var modal = $('#verifyModal');
        var verify_code = $(this).data('verify_code');
        var verify_detail = $(this).data('verify_detail');
        modal.find('.verify-code').html(`<h4 class="mb-3"> Domain Verification Code : ${verify_code} </h4>`);
        modal.find('.verify-detail').html(`<p> ${verify_detail} </p>`);
        modal.modal('show');
    });
</script>
@endpush
