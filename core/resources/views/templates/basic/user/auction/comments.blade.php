@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="seller-profile">
                    <div class="seller-profile__thumb">
                        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$domain->user->image,imagePath()['profile']['user']['size']) }}"
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
                        <p><i class="far fa-comments text--base me-1"></i>
                            {{ count($domain->comments) }}&nbsp;@lang('comments placed')
                        </p>
                        <p><i class="fas fa-gavel text--base me-1"></i> 0 @lang('bids placed')</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="custom--card">
                    <div class="card-header border-bottom-0">
                        <h6><i class="las la-clipboard-list"></i> @lang('Comment List')</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="comment-area mt-4">
                            <div class="comments">
                                @forelse ($domain->comments as $item)
                                <div class="comment">
                                    <div class="comment__avatar">
                                        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$item->user->image,imagePath()['profile']['user']['size']) }}"
                                            alt="image">
                                    </div>
                                    @php
                                    $replies = showReplyComment($item->id);
                                    @endphp
                                    <div class="comment__body">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                            <a href="#0" class="text--base">{{ __(@$item->user->username) }}</a>
                                            <p class="fs--14px">{{ $item->created_at->format('D,d M Y') }}
                                                <span class="ms-2 text--white btn btn--base btn-sm replyBtn"
                                                    data-bs-toggle="modal" data-id="{{ $item->id }}"
                                                    data-bs-target="#replyComment" data-original-title="@lang('Reply')">
                                                    <i class="las la-reply" data-bs-toggle="tooltip"
                                                        data-bs-position="top" title="Reply"></i></span>
                                            </p>
                                        </div>
                                        <p class="comment__details">{{ __($item->comment_detail) }}
                                        </p>
                                        @isset($replies)
                                        <br>
                                        @foreach ($replies as $item)
                                        <p class="comment__details">
                                            <span class="text--base">@lang('Replied:')</span>&nbsp;
                                            {{ __($item->reply_comment) }}
                                        </p>
                                        @endforeach
                                        @endisset
                                    </div>
                                </div>
                                @empty
                                <div class="no-message text-center mb-3">
                                    <h4 class="title fw-normal text--muted">{{ __($emptyMessage) }}</h4>
                                    <i class="far fa-comment-dots text--muted mt-2"></i>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="replyComment" class="modal fade" id="modal-8" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="text--dark">@lang('Reply this comment')</h6>
            </div>
            <form action="{{ route('user.auction.comments.reply') }}" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <input type="hidden" name="id" value="">
                    <textarea name="reply_comment" class="form--control"
                        placeholder="@lang('Write here')..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal"
                        aria-label="Close">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--base">@lang('Continue')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    "use script";
    $('.replyBtn').on('click', function() {
        var modal = $('#replyComment');
        modal.find('input[name=id]').val($(this).data('id'));
    });
</script>
@endpush
