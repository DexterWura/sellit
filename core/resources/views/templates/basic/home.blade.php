@extends($activeTemplate.'layouts.frontend')
@section('content')

@include($activeTemplate.'sections.banner')

@if ($sections->secs != null)
@foreach (json_decode($sections->secs) as $sec)
@include($activeTemplate.'sections.'.$sec)
@endforeach
@endif

@endsection
@push('script')
<script>
    'use strict';
    (function ($) {
        $('.subscribe-btn').on('click' , function(e){
            e.preventDefault()
            var email    = $('.subscribe-email').val();
            $.ajax({
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                url:"{{ route('subscribe.post') }}",
                method:"POST",
                data:{email:email},
                success:function(response)
                {
                    if(response.success) {
                        $('.subscribe-email').val('')
                        notify('success', response.success);
                    }else{
                        notify('error', response.error);
                    }
                }
            });
        });
    })(jQuery);
</script>
@endpush