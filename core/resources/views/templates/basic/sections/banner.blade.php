@php
$content = getContent('banner.content', true);
@endphp
<section class="hero"
    style="background-image: url('{{ getImage('assets/images/frontend/banner/' . $content->data_values->background_image, '1920x535') }}')">
    <div class="hero__el">
        <img src="{{ getImage('assets/images/frontend/banner/' . $content->data_values->banner_image, '350x430') }}"
            alt="image">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8 text-md-start text-center">
                <div class="overflow-hidden">
                    <h2 class="hero__title text-white wow slideInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
                        {{ __($content->data_values->heading) }}
                    </h2>
                </div>
                <div class="overflow-hidden">
                    <p class="text-white mt-1 wow slideInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
                        {{ __($content->data_values->subheading) }}
                    </p>
                </div>
                <div class="overflow-hidden">
                    <div class="btn--group justify-content-md-start justify-content-center mt-4 wow slideInUp"
                        data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <a href="{{ $content->data_values->button_one_url }}" class="btn btn--base">
                            {{ __($content->data_values->button_one) }}
                        </a>
                        <a href="{{ $content->data_values->button_two_url }}" class="btn btn--base">
                            {{ __($content->data_values->button_two) }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>