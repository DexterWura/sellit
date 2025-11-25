@extends($activeTemplate .'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
                <div class="card custom--card top--border-base">
                    <div class="card-body">
                        <form action="{{route('user.verify.email')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <p class="text-center">@lang('Your Email'): <strong>{{auth()->user()->email}}</strong>
                                </p>
                            </div>
                            <div class="form-group">
                                <label>@lang('Verification Code')</label>
                                <input type="text" name="email_verified_code" class="form--control" maxlength="7"
                                    id="code">
                            </div>
                            <div class="form-group mb-0">
                                <input type="submit" class="mt-2 btn btn--base w-100 text-center"
                                    value="@lang('Submit')">
                            </div>
                            <div class="form-group mt-2">
                                <p>@lang('Please check including your Junk/Spam Folder. if not found, you can')
                                    <a href="{{route('user.send.verify.code')}}?type=email" class="forget-pass">
                                        @lang('Resend code')</a>
                                </p>
                                @if ($errors->has('resend'))
                                <br />
                                <small class="text-danger">{{ $errors->first('resend') }}</small>
                                @endif
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