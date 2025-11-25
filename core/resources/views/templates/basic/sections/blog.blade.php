@php
$blog = getContent('blog.content', true);
$blogs = getContent('blog.element', false, 3, null);
@endphp
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __($blog->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($blogs as $blog)
            <div class="col-lg-4 col-md-6">
                <div class="blog-item">
                    <div class="blog-item__thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/thumb_' . $blog->data_values->blog_image, '344x228') }}" alt="image">
                    </div>
                    <div class="blog-item__content">
                        <h3 class="blog-item__title mt-4">
                            <a href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}">{{__($blog->data_values->title) }}</a>
                        </h3>
                        <a href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}" class="read-more-btn mt-3">@lang('Read More')</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
