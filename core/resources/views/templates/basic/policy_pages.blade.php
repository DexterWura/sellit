@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row">
            @php echo $page->data_values->details @endphp
        </div>
    </div>
</section>
@endsection