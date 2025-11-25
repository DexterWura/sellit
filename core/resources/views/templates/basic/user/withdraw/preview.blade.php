@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-8">
                <div class="add-money-card">
                    <form action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if($withdraw->method->user_data)
                        @foreach($withdraw->method->user_data as $k => $v)
                        @if($v->type == "text")
                        <div class="form-group col-lg-12">
                            <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span
                                        class="text-danger">*</span> @endif</strong></label>
                            <input type="text" name="{{$k}}" class="form--control" value="{{old($k)}}"
                                placeholder="{{__($v->field_level)}}" @if($v->validation == "required") required
                            @endif>
                            @if ($errors->has($k))
                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                            @endif
                        </div>
                        @elseif($v->type == "textarea")
                        <div class="form-group col-lg-12">
                            <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span
                                        class="text-danger">*</span> @endif</strong></label>
                            <textarea name="{{$k}}" class="form--control" placeholder="{{__($v->field_level)}}" rows="3"
                                @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                            @if ($errors->has($k))
                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                            @endif
                        </div>
                        @elseif($v->type == "file")
                        <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span
                                    class="text-danger">*</span> @endif</strong></label>
                        <div class="form-group col-lg-12">
                            <input type="file" name="{{$k}}" accept="image/*" @if($v->validation ==
                            "required") required @endif class="form-control custom--file-upload">
                            @if ($errors->has($k))
                            <br>
                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                            @endif
                        </div>
                        @endif
                        @endforeach
                        @endif
                        @if(auth()->user()->ts)
                        <div class="form-group">
                            <label>@lang('Google Authenticator Code')</label>
                            <input type="text" name="authenticator_code" class="form-control" required>
                        </div>
                        @endif
                        <div class="form-group">
                            <button type="submit" class="mt-2 btn btn--base w-100 text-center">
                                @lang('Submit')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="add-money-card style--two">
                    <h4 class="title"><i class="lar la-file-alt"></i> @lang('Summery')</h4>
                    <div class="add-moeny-card-middle">
                        <ul class="add-money-details-list">
                            <li>
                                <span class="caption">@lang('Request Amount :') </span>
                                <div class="value">
                                    <span class="show-amount">
                                        {{showAmount($withdraw->amount) }}&nbsp;{{ __($general->cur_text) }}
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('Withdrawal Charge :')</span>
                                <div class="value">
                                    <span class="charge">
                                        {{showAmount($withdraw->charge) }}&nbsp;{{__($general->cur_text)}}
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('After Charge :')</span>
                                <div class="value">
                                    <span class="charge">
                                        {{showAmount($withdraw->after_charge) }}&nbsp;{{__($general->cur_text)}}
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('Conversion Rate :')</span>
                                <div class="value">
                                    <span class="charge">
                                        {{showAmount($withdraw->rate) }}&nbsp;{{__($withdraw->currency)}}
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('You Will Get :')</span>
                                <div class="value">
                                    <span class="charge">
                                        {{showAmount($withdraw->final_amount) }}&nbsp;{{__($withdraw->currency)}}
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div class="add-money-details-bottom">
                            <span class="caption">@lang('Balance Will be')</span>
                            <div class="value">
                                <span class="payable delay">
                                    {{showAmount($withdraw->user->balance - ($withdraw->amount))}}&nbsp;{{
                                    $general->cur_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection