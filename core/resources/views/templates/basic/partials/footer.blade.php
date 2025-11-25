@php
$footer = App\Models\Frontend::where('data_keys', 'footer.content')->first();
$policyPages = getContent('policy_pages.element',false,null);
@endphp
<footer class="footer overflow-hidden">
    <div class="footer-el">
        <img src="{{ getImage('assets/images/frontend/footer/' . $footer->data_values->footer_image, '400x330') }}"
            alt="image">
    </div>

    <div class="footer__top">
        <div class="container">
            <div class="row gy-3 align-items-center">

                <div class="col-sm-4 text-sm-start text-center">
                    <a href="{{ route('home') }}" class="footer__logo">
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo image">
                    </a>
                </div>

                <div class="col-md-8 col-sm-8">
                    <div class="row justify-content-sm-end justify-content-center">
                        <div class="col-xl-2 col-md-3 col-sm-5 col-6 text-sm-end text-center">
                            <div class="footer-info d-inline-block text-center">
                                <h3 class="footer-info__amount fw-bold text--base">
                                    {{ __($footer->data_values->total_domain) }}</h3>
                                <span class="footer-info__caption">@lang('Total Domain')</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-3 col-sm-5 col-6 text-sm-end text-center">
                            <div class="footer-info d-inline-block text-center">
                                <h3 class="footer-info__amount fw-bold text--base">
                                    {{ __($footer->data_values->total_user) }}</h3>
                                <span class="footer-info__caption">@lang('Total User')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-5 text-md-start text-center">
                    @include($activeTemplate.'partials.copyright_text')
                </div>
                <div class="col-lg-6 col-md-7">
                    <ul
                        class="inline-menu d-flex flex-wrap justify-content-md-end justify-content-center align-items-center">
                        @forelse($policyPages as $item)
                        <li>
                            <a href="{{ route('page.details', [$item->id, @slug($item->data_values->title)]) }}">
                                {{__(@$item->data_values->title) }}
                            </a>
                        </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
