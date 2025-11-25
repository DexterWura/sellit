
@forelse ($allComment as $item)

<div class="comment">
    <div class="comment__avatar">
        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$item->user->image,null,true) }}"
            alt="image">
    </div>
    <div class="comment__body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <a href="#0" class="text--base">{{ __(@$item->user->username) }}</a>
            <p class="fs--14px">{{ $item->created_at->format('D,d M Y') }}
                <span class="ms-2 text--white btn btn--base btn-sm replyBtn" data-bs-toggle="modal"
                    data-id="{{ $item->id }}" data-bs-target="#replyComment" data-original-title="@lang('Reply')">
                    <i class="las la-reply" data-bs-toggle="tooltip" data-bs-position="top" title="Reply"></i></span>
            </p>
        </div>
        <p class="comment__details">{{ __($item->comment_detail) }}</p>

        @if(count($item->replies) > 0)
        <br>
        @foreach ($item->replies as $reply)
        <div class="d-flex flex-wrap justify-content-between align-items-center my-2">
            <a href="#0" class="text--base">{{ __(@$reply->user->username) }}</a>
            <p class="fs--14px">{{ $item->created_at->format('D,d M Y') }}
                <span class="ms-2 text--white btn btn--base btn-sm replyBtn" data-bs-toggle="modal"
                    data-id="{{ $item->id }}" data-bs-target="#replyComment" data-original-title="@lang('Reply')">
                    <i class="las la-reply" data-bs-toggle="tooltip" data-bs-position="top" title="Reply"></i></span>
            </p>
        </div>
        <p class="comment__details">
            <span class="text--base">@lang('Replied:')</span>&nbsp;
            {{ __($reply->comment_detail) }}
        </p>
        @endforeach
        @endif

    </div>
</div>
@empty
<div class="no-message text-center mb-3">
    <h4 class="title fw-normal text--muted">{{ __($emptyMessage) }}</h4>
    <i class="far fa-comment-dots text--muted mt-2"></i>
</div>
@endforelse