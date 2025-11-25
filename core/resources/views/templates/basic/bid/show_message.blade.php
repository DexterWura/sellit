@forelse ($messages as $item)
<div class="single-message">
    <div class="single-message__thumb">
        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. @$item->sender->image,null,true) }}"
            alt="image">
    </div>
    <div class="single-message__content">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">

            @if ($item->admin_id == 1)
                <span class="text--base">@lang('Admin')</span>
            @else
                <span class="text--base">{{ __(@$item->sender->username) }}</span>
            @endif

            <p class="fs--14px">{{ __($item->created_at->format('D, d M Y')) }}</p>
        </div>
        <p class="single-message__details fs--15px">{{ $item->message }}</p>
        @if(json_decode($item->attachment) > 0)
        <div class="mt-2">
            @foreach(json_decode($item->attachment) as $k=> $fileName)
            <a href="{{route('user.bid.conversation.attachment.download', [$fileName, $item->id])}}" class="text--dark me-2"><i class="fa fa-file text--base"></i>
                @lang('Attachment') {{++$k}}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@empty
<div class="no-message text-center">
    <h4 class="title fw-normal text--muted">{{ __($emptyMessage) }}</h4>
    <i class="far fa-comment-dots text--muted mt-2"></i>
</div>
@endforelse
