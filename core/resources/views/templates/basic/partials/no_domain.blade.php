@php
$image = getContent('empty_message.content',true);
@endphp
@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="no-data-wrapper text-center">
                    <img src="{{ getImage('assets/images/frontend/empty_message/' . $image->data_values->image, '400x330') }}"
                        alt="image">
                    <h3 class="title mt-3">{{ __($emptyMessage) }}</h3>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
