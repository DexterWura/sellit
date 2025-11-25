@php
$contact = getContent('contact_us.content',true);
$contacts = getContent('contact_us.element',false,null,true);
$socialIcon = getContent('social_icon.element',false,null,true);
@endphp
@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-5 text-lg-start text-center">
                <span class="text--base fs--18px fw-medium">{{ __($contact->data_values->title) }}</span>
                <h2 class="section-title mb-4">{{ __($contact->data_values->subtitle) }}</h2>
                <div class="row gy-3">
                    @foreach ($contacts as $item)
                    <div class="col-lg-12 col-md-4">
                        <div class="contact-card">
                            <div class="contact-card__icon">
                                @php
                                echo $item->data_values->icon;
                                @endphp
                            </div>
                            <div class="contact-card__content">
                                <h5 class="title mb-1">{{ __($item->data_values->title) }}</h5>
                                <p>{{ __($item->data_values->detail) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <ul
                    class="social-link-list d-flex align-items-center justify-content-lg-start justify-content-center mt-4">
                    @foreach ($socialIcon as $item)
                    <li data-bs-toggle="tooltip" data-bs-position="top" title="{{ __($item->data_values->title) }}">
                        <a href="{{ __($item->data_values->url) }}">
                            @php
                            echo $item->data_values->social_icon;
                            @endphp
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-7">
                <div class="contact-form-wrapper">
                    <h2 class="section-title">{{ __($contact->data_values->form_heading) }}</h2>
                    <p>{{ __($contact->data_values->form_subheading) }}</p>
                    <form class="contact-form mt-4 needs-validation" method="post" action="">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <label>@lang('Name')<span class="text--danger">*</span></label>
                                <input type="text" name="name" autocomplete="off" class="form--control"
                                    placeholder="@lang('Enter full name')"
                                    value="@if(auth()->user()){{ auth()->user()->fullname }}@else {{ old('name') }}@endif"
                                    @if(auth()->user()) readonly @endif required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6">
                                <label>@lang('Email')<span class="text--danger">*</span></label>
                                <input type="email" name="email" autocomplete="off" class="form--control"
                                    placeholder="@lang('Enter email address')"
                                    value="@if(auth()->user()){{ auth()->user()->email }}@else{{old('email')}} @endif"
                                    @if(auth()->user()) readonly @endif required>
                            </div>

                            <div class="form-group col-lg-12">
                                <label>@lang('Subject')<span class="text--danger">*</span></label>
                                <input type="text" name="subject" autocomplete="off" class="form--control"
                                    placeholder="@lang('Enter subject')" value="{{old('subject')}}" required>
                            </div>

                            <div class="form-group col-lg-12">
                                <label>@lang('Message')<span class="text--danger">*</span></label>
                                <textarea name="message" class="form--control"
                                    placeholder="@lang('Write your message')">{{old('message')}}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div><!-- row end -->
    </div>
</section>
<div class="map-area">
    <iframe
        src="https://maps.google.com/maps?q={{ __($contact->data_values->latitude) }},{{ __($contact->data_values->longitude) }}&hl=es;z=14&amp;output=embed"></iframe>
</div>

@if ($sections->secs != null)
@foreach (json_decode($sections->secs) as $sec)
@include($activeTemplate.'sections.'.$sec)
@endforeach
@endif

@endsection
