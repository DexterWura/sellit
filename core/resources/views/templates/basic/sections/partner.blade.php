@php
$partner = getContent('partner.content', true);
$partnerImage = getContent('partner.element', false, null);
@endphp
<section class="brand-section section--bg">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-xl-2 col-lg-3 text-lg-start text-center">
                <h2>{{ __($partner->data_values->heading) }}</h2>
            </div>
            <div class="col-xl-10 col-lg-9">
                <div class="brand-slider">
                    @foreach ($partnerImage as $partner)
                    <div class="single-slide">
                        <div class="brand-item">
                            <img src="{{ getImage('assets/images/frontend/partner/' . $partner->data_values->partner_image, '170x35') }}"
                                alt="image">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>