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
                        <p><i class="far fa-comments text--base me-1"></i>
                            {{ $conversations->count() }}&nbsp;@lang('users discussed about this')
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="custom--card">
                    <div class="card-header border-bottom-0">
                        <h6><i class="las la-clipboard-list"></i> @lang('Message Sender List')</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="comment-area mt-4">
                            <div class="comments">
                                <div class="table-responsive table-responsive--md">
                                    <table class="table custom--table">
                                        <thead>
                                            <tr>
                                                <th>@lang('S.N.')</th>
                                                <th>@lang('Name')</th>
                                                <th>@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($conversations as $conversation)
                                                <tr>
                                                    <td data-label="@lang('S.N.')">
                                                        <span class="text--info">{{ @$conversations->firstItem() + $loop->index }}</span>
                                                    </td>

                                                    <td data-label="@lang('Name')">
                                                        <span class="text--info">{{ @$conversation->sender->fullname }}</span>
                                                    </td>

                                                    <td data-label="@lang('Action')">
                                                        <a href="{{ route('user.contact.seller.owner.conversations',[$conversation->domain_id, $conversation->domain->name]) }}" class="icon-btn bg--base" data-bs-toggle="tooltip" data-bs-position="top" title="See Convertion"><i class="las la-comment-alt"></i>
                                                        </a>
                                                        @if ($conversation->message_count > 0)
                                                        <span class="has-notification"></span>
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
                    </div>
                </div>
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
        top: -23px;
        right: 2px;
        width: 13px;
        height: 13px;
        border: 2px solid #fff;
        background-color: red;
        border-radius: 50%;
    }
</style>
@endpush
@push('script')
<script>
    "use script";
    $('.replyBtn').on('click', function() {
        var modal = $('#replyComment');
        modal.find('input[name=id]').val($(this).data('id'));
    });
</script>
@endpush
