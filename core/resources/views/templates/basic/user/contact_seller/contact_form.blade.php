@php
$items = getContent('contact_seller.element',false,null,true);
$content = getContent('contact_seller.content',true);
@endphp
@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="contact-seller-sidebar">
                    <div class="contact-seller-widget bg-white">
                        <h4 class="mb-2">{{ __($domain->name) }}</h4>
                        <ul class="caption-list mt-3">
                            <li>
                                <span class="caption">@lang('Price')</span>
                                <span class="value">{{ $general->cur_sym }}{{ showAmount($domain->price) }}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Ends')</span>
                                <span class="value" data-countdown="{{ $domain->end_time }}" data-title="@lang('This auction ended')"></span>
                            </li>
                        </ul>

                        <a href="{{ route('user.make.bid',[$domain->id,slug($domain->name)]) }}"
                            class="btn btn-md btn--base w-100 mt-3">
                            @lang('Bid Now')
                        </a>

                    </div>
                    <div class="contact-seller-widget bg-white mt-4">
                        <ul class="buyer-instruction-list">
                            @foreach ($items as $item)
                            <li>
                                <div class="icon text--base">@php echo $item->data_values->icon; @endphp</div>
                                <div class="content">
                                    <h6 class="title">{{ __($item->data_values->title) }}</h6>
                                    <p>{{ __($item->data_values->details) }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="chat-box bg-white">
                    <h4 class="mb-3">@lang('Discussion with') {{ __(@$domain->user->fullname) }}</h4>

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
</section>
@endsection
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

        function fetchMessage(){
            $.ajax({
                type: "GET",
                url: "{{ route('user.contact.seller.user.message.all',$domain->id) }}",
                success: function (response) {
                    $('#showMessage').html(response);
                }
            });
        }

        $("#messageForm").submit(function (e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                url:"{{ route('user.contact.seller.message.user.send', [$domain->id]) }}",
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
                        fetchMessage();
                    }else{
                        notify('error',response.error);
                    }
                }
            });
        });
</script>
@endpush
