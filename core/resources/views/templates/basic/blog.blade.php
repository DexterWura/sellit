@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row gy-4 align-items-center">
            @foreach ($blogs as $item)
            <div class="col-lg-4 col-md-6">
                <div class="blog-item">
                    <div class="blog-item__thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/thumb_' . $item->data_values->blog_image, '344x228') }}" alt="image">
                    </div>
                    <div class="blog-item__content">
                        <h3 class="blog-item__title mt-4">
                            <a href="{{ route('blog.details',[$item->id,slug($item->data_values->title)]) }}">{{
                                __($item->data_values->title) }}
                            </a>
                        </h3>
                        <a href="{{ route('blog.details',[$item->id,slug($item->data_values->title)]) }}" class="read-more-btn mt-3">@lang('Read More')</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{ $blogs->links() }}
    </div>
</section>


@if ($sections->secs != null)
    @foreach (json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif

@endsection
