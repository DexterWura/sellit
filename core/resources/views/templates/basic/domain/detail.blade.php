@php
    $steps = getContent('how_to_buy_domain.element',false,null,true);
@endphp
@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="list-details-header">
                    <div class="list-details-header__top">
                        <div class="left">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h3 class="title mb-0">{{ __($domain->display_name) }}</h3>
                                <span class="badge badge--{{ $domain->listing_type === 'domain' ? 'primary' : ($domain->listing_type === 'website' ? 'success' : 'info') }}">
                                    {{ __($domain->listing_type_name) }}
                                </span>
                            </div>
                            <p class="fs--14px mt-2">
                                <span><i class="fas fa-map-marker-alt"></i> {{ __($domain->location) }}</span>
                                @if($domain->is_website && $domain->website_url)
                                    <span class="ms-3"><i class="fas fa-link"></i> <a href="http://{{ $domain->website_url }}" target="_blank">{{ $domain->website_url }}</a></span>
                                @endif
                                @if($domain->is_social_media)
                                    <span class="ms-3"><i class="fab fa-{{ $domain->social_platform }}"></i> {{ ucfirst($domain->social_platform) }}: {{ $domain->social_username }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="right text-center">
                            <div class="list-details-header__price mb-2">
                                {{ $general->cur_sym }}{{ showAmount($domain->price) }}
                            </div>
                            <a href="{{ route('user.contact.seller.user',[$domain->id, slug($domain->name)]) }}"
                                class="btn btn-sm btn-outline--base w-100">
                                @lang('Contact Seller')
                            </a>
                        </div>
                    </div>

                    <div class="list-details-header__bottom">
                        <ul
                            class="list-details-header__info d-flex flex-wrap justify-content-between align-items-center">
                            <li>
                                <p>@lang('Registered At')</p>
                                <span class="fw-bold">{{ diffForHumans($domain->register_date) }}</span>
                            </li>
                            <li>
                                <p>@lang('Category')</p>
                                <span class="fw-bold">{{ __(@$domain->category->name) }}</span>
                            </li>
                            <li>
                                <p>@lang('Traffic')</p>
                                <span class="fw-bold">{{ $domain->traffic }}</span>
                            </li>
                            <li>
                                <p>@lang('Total Bids')</p>
                                <span class="fw-bold">{{ count($domain->bids) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="list-details-wrapper">
                    <div class="list-details-content-block">
                        <h5 class="mb-2">@lang('Listing Details')</h5>
                        <P> @php echo $domain->description; @endphp </P>
                    </div>
                    
                    @if($domain->analytics_data && is_array($domain->analytics_data))
                    <div class="list-details-content-block">
                        <h5 class="mb-2">@lang('Analytics & Metrics')</h5>
                        <div class="row">
                            @foreach($domain->analytics_data as $key => $value)
                            <div class="col-md-6 mb-3">
                                <div class="analytics-item">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                    <span class="ms-2">{{ is_numeric($value) ? number_format($value) : $value }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($domain->additional_links && is_array($domain->additional_links) && count($domain->additional_links) > 0)
                    <div class="list-details-content-block">
                        <h5 class="mb-2">@lang('Additional Links')</h5>
                        <ul class="list-unstyled">
                            @foreach($domain->additional_links as $link)
                            <li class="mb-2">
                                <a href="{{ $link }}" target="_blank" class="text--base">
                                    <i class="fas fa-external-link-alt"></i> {{ $link }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($domain->note)
                    <div class="list-details-content-block">
                        <h5 class="mb-2">@lang('Seller\'s note')</h5>
                        <p>{{ __($domain->note) }}</p>
                    </div>
                    @endif
                </div>

                @auth
                <div class="comment-area mt-4">
                    <h5 class="mb-3">@lang('Comment')</h5>
                    <div class="comments" id="showComment"></div>
                    <form class="comment-form">
                        <div class="form-group">
                            <label class="fs--18px">@lang('Ask a question')</label>
                            <textarea name="comment_detail" class="form--control commentDetail"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn--base commentSubmit">@lang('Comment Publicly')</button>
                        </div>
                    </form>
                </div>
                @else
                <div class="comment-area mt-4">
                    <h5 class="mb-3">@lang('Comment')</h5>
                    <p>@lang('Please')
                        <a href="{{ route('user.login') }}" class="text--base fw-medium">@lang('login')</a> @lang('to submit your comment.')
                    </p>
                </div>
                @endauth

            </div>
            <div class="col-lg-4 ps-xl-4">

                <div class="offer-widget mb-4">
                    <h5 class="offer-widget__title">@lang('Make an offer on this Domain')</h5>
                    <div id="timer" class="offer-widget__body">
                        <span id="days"></span>
                        <span id="hours"></span>
                        <span id="minutes"></span>
                        <span id="seconds"></span>
                    </div>
                    @if($domain->user_id != auth()->id())
                    <div class="offer-widget__body">
                        <form action="{{ route('user.make.bid.store', $domain->id) }}" method="POST"
                            class="mb-4 offer-widget__form">
                            @csrf

                            <div class="input-group">
                                <span class="input-group-text border-0 text-white">{{ $general->cur_sym }}</span>
                                <input type="text" type="number" step="any" name="offer_price" autocomplete="off" class="form-control form--control" placeholder="@lang('Enter offer price')" required>
                                <button type="submit" class="input-group-text btn--base border-0">@lang('Bid Now')</button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

                <div class="seller-widget ">
                    <h6 class="mb-2">@lang('Seller Information')
                    </h6>
                    <div class="seller-widget__top">
                        <div class="thumb">
                            <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$domain->user->image,null,true) }}"
                                alt="image">
                        </div>
                        <div class="content">
                            <h5 class="name">{{ __(@$domain->user->fullname) }}</h5>
                            <p class="fs--14px mt-1">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ __(@$domain->user->address->country) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="buying-widget mt-4">
                    <h5 class="mb-3">@lang('Buy this Domain in') {{ count($steps) }} @lang('Easy Steps')</h5>
                    <ul class="buying-advice">
                        @foreach ($steps as $item)
                        <li>
                            <h6 class="title fs--16px">{{ __($item->data_values->title) }}</h6>
                            <p class="fs--15px mt-2">{{ __($item->data_values->details) }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="replyComment" class="modal fade" id="modal-8" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="text--dark">@lang('Reply this comment')</h6>
                <button type="button" class="btn-close btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.comments.reply',$domain->id) }}" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <input type="hidden" name="comment_id" value="">
                    <textarea name="reply_comment" class="form--control"
                        placeholder="@lang('Write here....')"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--base btn-sm w-100">@lang('Reply')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    'use strict';
    (function ($) {
        var domain_id = "{{ $domain->id }}";
        fetchComment();
        function fetchComment() {
            $.ajax({
                type: "GET",
                url: "{{ url('/comment/show/') }}" + '/' + domain_id,
                success: function (response) {
                    $('#showComment').html(response);
                }
            });
        }
        $('.commentSubmit').on('click' , function(e){
            e.preventDefault()
            var commentDetail = $('.commentDetail').val();
            $.ajax({
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                url:"{{ route('user.comment.post') }}",
                method:"POST",
                data:{comment_detail:commentDetail,domain_id:domain_id},
                success:function(response)
                {
                    if(response.success) {
                        $('.commentDetail').val('');
                        notify('success', response.success);
                        fetchComment();
                    }else{
                        notify('error', response.error);
                    }
                }
            });
        });
        function countdownTime() {
            var endTime = "{{ $domain->end_time }}";
            endTime = (Date.parse(endTime) / 1000);
            var now = new Date();
            now = (Date.parse(now) / 1000);
            var timeLeft = endTime - now;
            var days = Math.floor(timeLeft / 86400);
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));

            if (hours < "10") { hours = "0" + hours; }
            if (minutes < "10") { minutes = "0" + minutes; }
            if (seconds < "10") { seconds = "0" + seconds; }

            $("#days").html(`<span class="text--base pe-1">${days}</span><span>Days</span>`);
            $("#hours").html(`<span class="text--base pe-1">${hours}</span><span>Hours</span>`);
            $("#minutes").html(`<span class="text--base pe-1">${minutes}</span><span>Minutes</span>`);
            $("#seconds").html(`<span class="text--base pe-1">${seconds}</span><span>Seconds</span>`);
        }
        setInterval(function() { countdownTime(); }, 1000);

        $(document).on('click', '.replyBtn', function (e) {
            e.preventDefault();
            var comment_id = $(this).data('id');
            $("input[name='comment_id']").val(comment_id);
        });

    })(jQuery);
</script>
@endpush
