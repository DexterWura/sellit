@php
$about = getContent('about.content', true);
@endphp
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row gy-4 justify-content-center align-items-center">
            <div class="col-lg-6">
                <div class="about-thumb text-center">
                    <img src="{{ getImage('assets/images/frontend/about/' . $about->data_values->about_image, '450x575') }}"
                        alt="image">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-header mb-4">
                    <h2 class="section-title">{{ __($about->data_values->heading) }}</h2>
                    <p class="fw-medium fs--18px mt-2 text--h">{{ __($about->data_values->subheading) }}</p>
                </div>
                <p>{{ __($about->data_values->description) }}</P>
                <a href="{{ $about->data_values->btn_url }}" class="btn btn--base mt-3">
                    {{ __($about->data_values->btn_text) }}
                </a>
            </div>
        </div>
    </div>
</section>