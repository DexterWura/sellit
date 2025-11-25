@php
$content = getContent('empty_message.content',true);
@endphp
@forelse($domains as $domain)
<div class="domain-list-single">
    <div class="left">
        <div class="domain">
            <a class="text--base" href="{{ route('domain.detail',['id'=>$domain->id,'name'=>slug($domain->name)]) }}">
                {{ __($domain->name) }}
            </a>

        </div>
        <div class="traffic">{{ $domain->traffic }}</div>
        <div class="bid">{{ count($domain->bids) }}</div>
    </div>
    <div class="right">
        <div class="price"><b>{{ $general->cur_sym }}{{ showAmount($domain->price) }}</b></div>
        <div class="action">
            @if($domain->user_id == auth()->id())
            <span data-bs-toggle="tooltip" title="@lang('You can\'t bid on your domain')">
            <a href="javascript:void(0)" class="btn btn-sm btn-outline--base py-1 px-3 disabled">@lang('Bid Now')</a></span>
            @else
            <a href="{{ route('user.make.bid',[$domain->id,slug($domain->name)]) }}" class="btn btn-sm btn-outline--base py-1 px-3">@lang('Bid Now')</a>
            @endif
        </div>
    </div>
</div>
@empty
<div class="no-data-wrapper text-center">
    <img src="{{ getImage('assets/images/frontend/empty_message/' . $content->data_values->image, '400x330') }}"
        alt="image">
    <h3 class="title mt-3">{{ __($emptyMessage) }}</h3>
</div>
@endforelse
{{ $domains->links() }}
