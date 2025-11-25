@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
                <div class="card custom--card top--border-base">
                    <div class="card-body">
                        <form method="post" action="{{ route('user.password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label>@lang('Select One:')</label>
                                <select class="form--control" name="type">
                                    <option value="email">@lang('E-Mail Address')</option>
                                    <option value="username">@lang('Username')</option>
                                </select>
                            </div>
                            <div class="form-group hover-input-popup">
                                <label class="my_value"></label>
                                <input type="text" class="form--control @error('value') is-invalid @enderror"
                                    name="value" value="{{ old('value') }}" required autofocus="off" autocomplete="off">
                            </div>
                            <div class="form-group mb-0">
                                <input type="submit" class="mt-2 btn btn--base w-100 text-center"
                                    value="@lang('Submit')">
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
        myVal();
        $('select[name=type]').on('change',function(){
            myVal();
        });
        function myVal(){
            $('.my_value').text($('select[name=type] :selected').text());
        }
    })(jQuery)
</script>
@endpush