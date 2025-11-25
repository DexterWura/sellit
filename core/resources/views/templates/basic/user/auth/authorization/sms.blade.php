@extends($activeTemplate .'layouts.frontend')
@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
            <div class="card">
                <div class="card-header">@lang('Please Verify Your Mobile to Get Access')</div>

                <div class="card-body">

                    <form action="{{route('user.verify.sms')}}" method="POST" class="login-form">
                        @csrf

                        <div class="form-group">
                            <p class="text-center">@lang('Your Mobile Number'):
                                <strong>{{auth()->user()->mobile}}</strong>
                            </p>
                        </div>


                        <div class="form-group">
                            <label>@lang('Verification Code')</label>
                            <input type="text" name="sms_verified_code" id="code" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="btn-area text-center">
                                <button type="submit" class="btn btn-success">@lang('Submit')</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <p>@lang('If you don\'t get any code'), <a
                                    href="{{route('user.send.verify.code')}}?type=phone" class="forget-pass"> @lang('Try
                                    again')</a></p>
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
</div> --}}
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
                <div class="card custom--card top--border-base">
                    <div class="card-body">
                        <form action="{{route('user.verify.sms')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <p class="text-center">@lang('Your Mobile Number'):
                                    <strong>{{auth()->user()->mobile}}</strong>
                                </p>
                            </div>
                            <div class="form-group">
                                <label>@lang('Verification Code')</label>
                                <input type="text" name="sms_verified_code" id="code" class="form--control">
                            </div>
                            <div class="form-group mb-0">
                                <input type="submit" class="mt-2 btn btn--base w-100 text-center"
                                    value="@lang('Submit')">
                            </div>
                            <div class="form-group mt-2">
                                <p>@lang('If you don\'t get any code'),
                                    <a href="{{route('user.send.verify.code')}}?type=phone" class="forget-pass">
                                        @lang('Try
                                        again')
                                    </a>
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