@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100">
    <div class="container">
        <div class="card custom--card section--bg">
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-5">
                        <div class="user-profile style--two">
                            <div class="profile-thumb-wrapper text-center">
                                <div class="profile-thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"
                                            style="background-image: url('{{ getImage(imagePath()['profile']['user']['path'].'/'. @$messages[0]->user->image,null,true) }}')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="profile-info-list">

                                <li>
                                    <span class="caption"><i class="lar la-user"></i> @lang('Username')</span>
                                    <span class="details">{{ __(@$conversation->user->username) }}</span>
                                </li>


                                <li>
                                    <span class="caption"><i class="las la-hourglass-half"></i> @lang('Ends')</span>
                                    <span class="details" data-countdown="{{ @$conversation->domain->end_time }}"
                                        data-title="@lang('The auction end')"></span>
                                </li>


                                <li>
                                    <span class="caption"><i class="las la-envelope"></i> @lang('E-mail')</span>
                                    <span class="details">{{ __(@$conversation->user->email) }}</span>
                                </li>

                                <li>
                                    <span class="caption"><i class="las la-phone"></i> @lang('Phone')</span>
                                    <span class="details">@lang('+'){{ __(@$conversation->user->mobile) }}</span>
                                </li>

                                <li>
                                    <span class="caption"><i class="las la-flag"></i> @lang('Country')</span>
                                    <span class="details">{{ __(@$conversation->user->address->country) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7 ps-xl-4">

                        <div class="chat-box bg-white">
                            <div class="d-flex justify-content-between mb-3">
                                <h4>@lang('Domain'): {{ __(@$conversation->domain->name) }}</h4>
                                <h5>@lang('Price'): {{ $general->cur_sym }}{{ showAmount(@$conversation->domain->price) }}</h5>
                            </div>

                            <h6 class="mb-3">@lang('Conversation with') {{ __(@$conversation->user->username) }}
                            </h6>
                            <form method="post" enctype="multipart/form-data" id="messageForm">
                                <div class="form-group">
                                    <textarea name="message" class="form--control messageVal"
                                        placeholder="@lang('Type your message here')..."></textarea>
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

                            <div class="chat-message mt-4" id="showMessage">
                                @include($activeTemplate . 'user.contact_seller.messages')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
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
                <label for="chatFile-1">Browse</label>
                <input class="chat-file-input" name="attachments[]" type="file" id="chatFile-1" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt" multiple>
                <button type="button" class="chat-file-btn h-100"><i class="las la-times px-2"></i></button>
                </div>
            `);
            $('.chat-file-btn').on('click', function(){
                $(this).parent('.single-chat-file').remove()
            });
        });

        function fetchMessage(){
            $.ajax({
                type: "GET",
                url: "{{ route('user.contact.seller.owner.message.view', [$conversation->domain_id, $conversation->user_id]) }}",
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
                url:"{{ route('user.contact.seller.owner.message.send', [$conversation->domain_id, $conversation->user_id]) }}",
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
                        fetchMessage();
                    }else{
                        notify('error', response.error);
                    }
                }
            });
        });
</script>
@endpush


