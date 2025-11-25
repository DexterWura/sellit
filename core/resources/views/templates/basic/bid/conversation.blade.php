@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100">
    <div class="container">
        <div class="card custom--card section--bg">
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-5">
                        <div class="user-profile m-0 h-100">
                            <ul class="profile-info-list">
                                <li>
                                    <span class="caption"><i class="la la-globe"></i> @lang('Domain Name')</span>
                                    <span class="details">{{ __(@$bid->domain->name) }}</span>
                                </li>
                                <li>
                                    <span class="caption"><i class="las la-money-bill"></i> @lang('Domain
                                        Price')</span>
                                    <span class="details">{{ $general->cur_sym }}{{showAmount(@$bid->domain->price) }}</span>
                                </li>
                                <li>
                                    <span class="caption"><i class="lar la-user"></i> @lang('Seller Name')</span>
                                    <span class="details">{{ __(@$bid->domain->user->fullname) }}</span>
                                </li>
                                <li>
                                    <span class="caption"><i class="la la-user-circle"></i> @lang('Buyer Name')</span>
                                    <span class="details">{{ __(@$bid->user->fullname) }}</span>
                                </li>
                                <li>
                                    <span class="caption">
                                        <i class="las la-stop"></i> @lang('Bid Status')
                                    </span>

                                    @php
                                        echo $bid->statusText;
                                    @endphp
                                </li>

                                <li class="justify-content-center">
                                    @if ($bid->status == 0 && $bid->domain->user_id == auth()->id())
                                        <button class="btn btn--base giveCredential" data-bid_id="{{ $bid->id }}" data-domain_id="{{ $bid->domain_id }}">@lang('I\'ve Sent Credentials')</button>

                                    @elseif($bid->status == 2 && $bid->user_id == auth()->id())
                                        <button class="btn btn--warning bidReport me-2" data-bid_id="{{ $bid->id }}" data-domain_id="{{ $bid->domain_id }}">
                                            @lang('Report')
                                        </button>

                                        <button class="btn btn--base completeBid" data-bid_id="{{ $bid->id }}" data-domain_id="{{ $bid->domain_id }}">
                                            @lang('Completed')
                                        </button>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7 ps-xl-4">
                        <div class="row">
                            <div class="chat-box bg-white">
                                <h4 class="mb-3">@lang('Conversation For Domain')
                                </h4>

                                @if($bid->status == 0 || $bid->status == 2 || $bid->status == 8)
                                <form method="post" enctype="multipart/form-data" id="messageForm">
                                    <div class="form-group">
                                        <textarea name="message" class="form--control messageVal"
                                            placeholder="@lang('Type a message to the seller')"></textarea>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-8">
                                            <p class="mb-0 text--base fileupload-btn"><i class="fas fa-paperclip"></i>
                                                @lang('Attach file form your device')
                                            </p>
                                        </div>
                                        <div class="col-lg-4 text-right">
                                            <input type="submit" class="btn btn--base" value="Send Message">
                                        </div>
                                    </div>
                                    <div class="chat-file-box"></div>
                                </form>
                                @endif


                                <div class="chat-message mt-4" id="showMessage">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="giveCredentialModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <h6 class="text-center">@lang('Did you send all domain credentials?')</h6>
                    <input type="hidden" name="domain_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('No')</button>
                    <button class="btn btn--base btn-sm">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bidReportModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <h6 class="text-center">@lang('Are you sure to report against this bid?')</h6>
                    <input type="hidden" name="domain_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('No')</button>
                    <button class="btn btn--base btn-sm">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="completeBidModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <h6 class="text-center">@lang('Do you receive all credentials?')</h6>
                    <input type="hidden" name="domain_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-sm" data-bs-dismiss="modal">@lang('No')</button>
                    <button class="btn btn--base btn-sm">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('style')
<style>
    .chat-message {
        max-height: 331px;
        overflow-y: scroll;
    }
</style>
@endpush
@push('script')
<script>
    "use strict";
    $('.fileupload-btn').on('click', function(event) {
            $('.chat-file-box').html(`
                <div class="single-chat-file">
                <label for="chatFile-1">@lang('Browse')</label>
                <input class="chat-file-input" name="attachments[]" type="file" id="chatFile-1" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt" multiple>
                <button type="button" class="chat-file-btn h-100 px-3"><i class="las la-times"></i></button>
                </div>
            `);
            $('.chat-file-btn').on('click', function(){
                $(this).parent('.single-chat-file').remove()
            });
        });

        fetchConversation();

        function fetchConversation(){
            $.ajax({
                type: "GET",
                url: "{{ route('user.bid.conversation.show',$bid->id) }}",
                success: function (response) {
                    console.log(response)
                    $('#showMessage').html(response);
                }
            });
        }
        $("#messageForm").submit(function (e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                url:"{{ route('user.bid.conversation.store', [$bid->id,$bid->domain->id]) }}",
                method:"POST",
                data:formData,
                async:false,
                processData: false,
                contentType: false,
                success:function(response)
                {
                    if(response.success) {
                        $('.messageVal').val('');
                        $('.chat-file-box').html('');
                        notify('success', response.success);
                        fetchConversation();
                    }else{
                        notify('error', response.error);
                    }
                }
            });
        });



        $('.giveCredential').on('click', function() {
            var modal = $('#giveCredentialModal');
            modal.find('input[name="domain_id"]').val($(this).data('domain_id'));
            modal.find('form').attr('action', `{{ route('user.bid.credential','') }}/${$(this).data('bid_id')}`);
            modal.modal('show');
        });

        $('.bidReport').on('click', function() {
            var modal = $('#bidReportModal');
            modal.find('input[name="domain_id"]').val($(this).data('domain_id'));
            modal.find('form').attr('action', `{{ route('user.bid.report','') }}/${$(this).data('bid_id')}`);
            modal.modal('show');
        });

        $('.completeBid').on('click', function() {
            var modal = $('#completeBidModal');
            modal.find('input[name="domain_id"]').val($(this).data('domain_id'));
            modal.find('form').attr('action', `{{ route('user.bid.complete','') }}/${$(this).data('bid_id')}`);
            modal.modal('show');
        });


</script>
@endpush
