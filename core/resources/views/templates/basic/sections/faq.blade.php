@php
$faq = getContent('faq.content', true);
$faqs = getContent('faq.element', false, null);
@endphp

<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __($faq->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @foreach ($faqs as $faq)
                    @if($loop->odd)
                    <div class="bg-white mb-3">
                        <div class="faq-single">
                            <div class="faq-single__header">
                                <h6 class="faq-single__title">{{ __($faq->data_values->question) }}</h6>
                            </div>
                            <div class="faq-single__content">
                                <p>{{ __($faq->data_values->answer) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="col-lg-6">
                @foreach ($faqs as $faq)
                    @if($loop->even)
                    <div class="bg-white mb-3">
                        <div class="faq-single">
                            <div class="faq-single__header">
                                <h6 class="faq-single__title">{{ __($faq->data_values->question) }}</h6>
                            </div>
                            <div class="faq-single__content">
                                <p>{{ __($faq->data_values->answer) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
