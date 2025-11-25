@php
$content = getContent('why_choose_us.content', true);
$elements = getContent('why_choose_us.element', false, null);
@endphp
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __($content->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($elements as $element)
            <div class="col-lg-3 col-sm-6">
                <div class="choose-item text-center">
                    <div class="choose-item__icon text--base">
                        @php
                        echo $element->data_values->icon;
                        @endphp
                    </div>
                    <div class="choose-item__content">
                        <h4 class="title">{{ __($element->data_values->title) }}</h4>
                        <p class="mt-2">{{ __($element->data_values->subtitle) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
