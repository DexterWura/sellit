@php
$testimonials = getContent('testimonial.element', false, null);
@endphp
<div class="pt-80 pb-80 border-top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="testimonial-slider">
                    @foreach ($testimonials as $testimonial)
                    <div class="single-slide">
                        <div class="testimonial-item">
                            <div class="testimonial-item__thumb">
                                <img src="{{ getImage('assets/images/frontend/testimonial/' . $testimonial->data_values->user_image, '120x140') }}"
                                    alt="image">
                            </div>

                            <div class="testimonial-item__content">
                                <p class="testimonial-item__details">{{ __($testimonial->data_values->description) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>