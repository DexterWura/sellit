@php
use App\Models\Domain;

$domain = getContent('domain.content', true);
$newDomain = Domain::active()->latest()->take(8)->get();
$auctionEndDomain = Domain::active()->orderBy('id','asc')->take(8)->get();
$maxPriceDomain = Domain::active()->orderBy('price','desc')->take(8)->get();
@endphp
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <h2 class="section-title">{{ __($domain->data_values->heading) }}</h2>
                    <p>{{ __($domain->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            <div class="col-lg-4 col-md-6">
                <div class="domain-list">
                    <div class="domain-list__header">
                        <h3 class="domain-list__title">@lang('New Domains')</h3>
                    </div>
                    <ul class="domain-list__list">
                        @foreach ($newDomain as $domain)
                        <li>
                            <a href="{{ route('domain.detail',['id'=>$domain->id,'name'=>slug($domain->name)]) }}"
                                class="domain-name">
                                {{ __($domain->name )}}
                            </a>
                            <span class="domain-offer">
                                {{ showAmount($domain->price) }} {{ $general->cur_text}}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="domain-list">
                    <div class="domain-list__header">
                        <h3 class="domain-list__title">@lang('Auction Ending Soon')</h3>
                    </div>
                    <ul class="domain-list__list">
                        @foreach ($auctionEndDomain as $domain)
                        <li>
                            <a href="{{ route('domain.detail',['id'=>$domain->id,'name'=>slug($domain->name)]) }}"
                                class="domain-name">
                                {{ __($domain->name) }}
                            </a>
                            <span class="domain-offer">
                                {{ showAmount($domain->price) }} {{ $general->cur_text}}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="domain-list">
                    <div class="domain-list__header">
                        <h3 class="domain-list__title">@lang('High Rated Domains')</h3>
                    </div>
                    <ul class="domain-list__list">
                        @foreach ($maxPriceDomain as $domain)
                        <li>
                            <a href="{{ route('domain.detail',['id'=>$domain->id,'name'=>slug($domain->name)]) }}"
                                class="domain-name">
                                {{ __($domain->name) }}
                            </a>
                            <span class="domain-offer">
                                {{ showAmount($domain->price) }} {{ $general->cur_text }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 text-center">
                <a href="{{ route('domain.list') }}" class="btn btn-outline--base">
                    @lang('See More') <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
