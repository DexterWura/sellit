@extends('admin.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-4 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">{{__(@$bid->domain->name)}}</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Auction End')
                        <span class="font-weight-bold">{{ showDateTime(@$bid->domain->end_time) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Seller')
                        <span class="font-weight-bold">
                            <a href="{{ route('admin.users.detail', @$bid->domain->user_id) }}">
                                {{@$bid->domain->user->username }}
                            </a>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Buyer')
                        <span class="font-weight-bold">
                            <a href="{{ route('admin.users.detail', $bid->user_id) }}">
                                {{@$bid->user->username }}
                            </a>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Domain Price')
                        <span class="font-weight-bold">
                            {{ showAmount(@$bid->domain->price)}} {{__($general->cur_text)}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Buyer Offer Price')
                        <span class="font-weight-bold">
                            {{ showAmount($bid->price)}} {{__($general->cur_text)}}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @php
                            echo $bid->statusText;
                        @endphp
                    </li>

                </ul>
            </div>
        </div>

        @if($bid->status == 8)
            <div class="card b-radius--10 overflow-hidden box--shadow1 mt-5">
                <div class="card-header">
                    <h5 class="card-title m-0">@lang('Take Action')</h5>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger p-2" role="alert">
                        <p>@lang('The seller claimed that he had given the domain credential. But the buyer claimed that he didn\'t receive any credentials')</p>
                    </div>

                    <div class="d-flex flex-wrap gap-1">
                        <button class="btn btn-sm btn--danger flex-fill actionButton" data-status="1">
                            @lang('Domain Credential Given')
                        </button>

                        <button class="btn btn-sm btn--dark flex-fill actionButton" data-status="9">
                            @lang('Cancel This Auction')
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <div class="col-lg-8 mb-30">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Conversations')</h5>
                <div class="chat-box">
                    @forelse ($conversation as $item)
                    @if ($item->admin_id == 1)
                    <div class="single-message admin-message">
                        <div class="single-message__content">
                               <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                    <span class="text--primary">@lang('Admin')</span>
                                    <p class="fs--14px">{{ __($item->created_at->format('D, d M Y h:i A')) }}</p>
                                </div>
                                <p class="single-message__details fs--15px">{{ $item->message }}</p>
                            @if(json_decode($item->attachment) > 0)
                            <div class="mt-2">
                                @foreach(json_decode($item->attachment) as $k=> $fileName)
                                <a href="{{route('admin.domain.conversation.attachment.download', [$fileName, $item->id])}}" class="mr-3 text--dark"><i
                                        class="fa fa-file text--base"></i>
                                    @lang('Attachment') {{++$k}}
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="single-message">
                        <div class="single-message__content">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                <span class="text--primary">{{ __(@$item->sender->username) }}</span>
                                <p class="fs--14px">{{ __($item->created_at->format('D, d M Y h:i A')) }}</p>
                            </div>
                            <p class="single-message__details fs--15px">{{ $item->message }}</p>

                            @if(json_decode($item->attachment) > 0)
                            <div class="mt-2">
                                @foreach(json_decode($item->attachment) as $k=> $fileName)
                                <a href="{{route('admin.domain.conversation.attachment.download', [$fileName, $item->id])}}" class="mr-3 text--dark"><i
                                    class="fa fa-file text--base"></i>
                                @lang('Attachment') {{++$k}}
                            </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="no-message text-center">
                        <h4 class="title fw-normal text--muted">{{ __($emptyMessage) }}</h4>
                        <i class="far fa-comment-dots text--muted mt-2"></i>
                    </div>
                    @endforelse
                </div>

                @if($bid->status == 8)
                <div class="message-admin mt-5">
                    <form action="{{ route('admin.domain.bid.conversation.send-message') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bid_id" value="{{ $bid->id }}">
                        <div class="input-group mb-3">
                            <textarea name="message" class="form-control" placeholder="@lang('Write here')....."></textarea>
                        </div>
                        <div class="input-group">
                            <button type="submit" class="btn btn--primary w-100">@lang('Send Message')</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="credentialGiven" tabindex="-1" role="dialog" aria-labelledby="credentialGivenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="credentialGivenLabel">@lang('Confirmation Alert')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form method="post" action="{{ route('admin.domain.bid.report.action',$bid->id) }}">
                @csrf
                <input type="hidden" name="status">
                <div class="modal-body">
                    <p class="question"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
    .gap-1 {
        gap:5px;
    }
    .chat-box {
        max-height: 500px;
        overflow-y: scroll;
        scrollbar-width: thin;
        scrollbar-color: #ddd #fff;
    }
    .chat-box::-webkit-scrollbar {
        width: 12px;
    }

    .chat-box::-webkit-scrollbar-track {
        background: #fff;
    }

    .chat-box::-webkit-scrollbar-thumb {
        background-color: #ddd;
        border-radius: 20px;
        border: 3px solid #fff;
    }

    .single-message {
        width: 80%;
        padding: 20px;
        background-color: #f5f4fb;
        border-radius: 5px;
    }
    @media (max-width: 575px) {
        .single-message {
            width: 100%;
        }
    }
    .single-message + .single-message {
        margin-top: 15px;
    }

    .single-message.admin-message {
        margin-left: auto;
        background-color: #f7f7f7;
    }
</style>
@endpush
@push('script')
<script>
(function($){
    "use strict";
    $('.actionButton').on('click', function () {
        var modal = $('#credentialGiven');
        let status= $(this).data('status');
        modal.find('[name=status]').val(status);
        if(status == 1){
            $('.question').text(`@lang('Are you sure that domain credential is given?')`);
        }else{
            $('.question').text(`@lang('Are you sure to cancel this auction?')`);
        }
        modal.modal('show');
    });
})(jQuery);
</script>
@endpush
