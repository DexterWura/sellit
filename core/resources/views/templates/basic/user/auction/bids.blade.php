@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="seller-profile">
                    <div class="seller-profile__thumb">
                        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$domain->user->image,null,true) }}"
                            alt="image">
                    </div>
                    <div class="seller-profile__left-content">
                        <h4 class="name">{{ __($domain->name) }}</h4>
                        <ul class="seller-profile__info-list d-flex flex-wrap align-items-center">
                            <li>@lang('Location:') {{ __($domain->location) }}</li>
                            <li>@lang('Posted:')&nbsp;{{ __($domain->created_at->format('D, d M Y')) }}</li>
                        </ul>
                    </div>
                    <div class="seller-profile__middle-content">

                    </div>
                    <div class="seller-profile__right-content">
                        <p><i class="fas fa-gavel text--base me-1"></i> {{ count($domain->bids) }} @lang('bids placed')
                        </p>
                        <p>@lang('Price :') {{ showAmount($domain->price) }}&nbsp;{{ $general->cur_text }}
                        </p>
                        <p>
                            @if ($domain->status == 2)
                            <span class="badge badge--success">@lang('Sold')</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="custom--card">
                    <div class="card-header border-bottom-0">
                        <h6><i class="las la-clipboard-list"></i> @lang('List')</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Date')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('More')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userBids as $bid)
                                    <tr>
                                        <td data-label="@lang('Name')">{{__(@$bid->user->username)}}</td>
                                        <td data-label="@lang('Price')">
                                            {{ showAmount($bid->price) }}&nbsp;{{__($general->cur_text)}}</td>
                                        <td data-label="@lang('Date')">{{ showDateTime($bid->created_at) }}
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if($bid->win_status == 0)
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($bid->win_status == 1)
                                            <span class="badge badge--success">@lang('Win')</span>
                                            @else
                                            <span class="badge badge--danger">@lang('Lose')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('More')">
                                            @if($bid->win_status == 0)
                                            <button class="icon-btn btn--success approveBtn" data-id="{{ $bid->id }}">
                                                <i class="las la-check" data-bs-toggle="tooltip" data-bs-position="top" title="@lang('Make Win')"></i>
                                            </button>
                                            @elseif($bid->win_status == 1)
                                            <a href="{{ route('user.bid.conversation',['id'=>$bid->id,'domain_id'=>$domain->id]) }}" class="icon-btn bg--base">
                                                <i class="las la-sms" data-bs-toggle="tooltip" data-bs-position="top" title="@lang('Send Message')"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                {{ paginateLinks($userBids) }}
            </div>
        </div>
    </div>
</div>
<div id="approveDomain" class="modal fade" id="modal-8" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Confirmation Alert!')</h5>
                <button type="button" class="btn-close btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <h6 class="">@lang('Are you sure to make win this bid?')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger  btn-sm" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--success btn-sm">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    "use script";
    $('.approveBtn').on('click', function() {
        var modal = $('#approveDomain');
        modal.find('form').attr('action', `{{ route('user.bid.approve','') }}/${$(this).data('id')}`);
        modal.modal('show');
    });
</script>
@endpush
