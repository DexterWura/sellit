@php
$item = getContent('subscribe.content', true);
@endphp
<section class="subscribe-section bg_img"
    style="background-image: url('{{ getImage('assets/images/frontend/subscribe/' . $item->data_values->background_image, '1920x370') }}');">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 text-lg-start text-center">
                <h2 class="text-white">{{ __($item->data_values->heading) }}</h2>
                <p class="text-white">{{ __($item->data_values->subheading) }}
                </p>
            </div>
            <div class="col-lg-6">
                <form class="subscribe-form mt-4">
                    <input type="email" name="email" autocomplete="off" class="form--control subscribe-email"
                        placeholder="@lang('Enter email address')">
                    <button type="submit" class="btn btn--base fs--14px subscribe-btn">@lang('Subscribe')</button>
                </form>
            </div>
        </div>
    </div>
</section>