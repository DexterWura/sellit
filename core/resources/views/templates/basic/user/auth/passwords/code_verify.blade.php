@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
                <div class="card custom--card top--border-base">
                    <div class="card-body">
                        <form action="{{ route('user.password.verify.code') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <div class="form-group">
                                <label>@lang('Verification Code')</label>
                                <input type="text" class="form--control" name="code" id="code" required autofocus="off">
                            </div>
                            <div class="form-group mb-0">
                                <input type="submit" class="mt-2 btn btn--base w-100 text-center"
                                    value="@lang('Submit')">
                            </div>
                            <div class="form-group mt-2">
                                @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                <a href="{{ route('user.password.request') }}" class="text--base">@lang('Try to send
                                    again')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;
          $(this).val(function (index, value) {
             value = value.substr(0,7);
              return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
          });
      });
    })(jQuery)
</script>
@endpush