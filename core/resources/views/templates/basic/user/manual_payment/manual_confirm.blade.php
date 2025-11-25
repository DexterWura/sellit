@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card custom--card top--border-base">
                    <div class="card-body">

                        <div class="col-md-12 text-center">
                            <p class="text-center mt-2">@lang('You have requested')
                                <b class="text--success">
                                    {{ showAmount($data['amount']) }} {{__($general->cur_text)}}
                                </b>,@lang('Please pay')
                                <b class="text--success">
                                    {{showAmount($data['final_amo']) .' '.$data['method_currency'] }}
                                </b> @lang('for successful payment')
                            </p>
                            <h4 class="text-center mb-4">@lang('Please follow the instruction below')</h4>
                            <p class="my-4 text-center">@php echo $data->gateway->description @endphp</p>
                        </div>
                        <form action="{{ route('user.deposit.manual.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @if($method->gateway_parameter)
                            @foreach(json_decode($method->gateway_parameter) as $k => $v)
                            @if($v->type == "text")
                            <div class="form-group col-lg-12">
                                <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required')
                                        <span class="text--danger">*</span> @endif</strong></label>
                                <input type="text" class="form--control" name="{{$k}}" value="{{old($k)}}"
                                    placeholder="{{__($v->field_level)}}">
                            </div>
                            @elseif($v->type == "textarea")
                            <div class="form-group col-lg-12">
                                <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required')
                                        <span class="text--danger">*</span> @endif</strong></label>
                                <textarea name="{{$k}}" class="form--control" placeholder="{{__($v->field_level)}}"
                                    rows="3">{{old($k)}}</textarea>

                            </div>
                            @elseif($v->type == "file")
                            <div class="form-group col-lg-12">
                                <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span
                                            class="text--danger">*</span> @endif</strong>
                                </label>
                                <input type="file" name="{{$k}}" accept="image/*"
                                    class="form-control custom--file-upload">
                            </div>
                            @endif
                            @endforeach
                            @endif
                            <div class="form-group mb-0">
                                <button type="submit" class="mt-2 btn btn--base w-100 text-center">@lang('Pay
                                    Now')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('style')
<style>
    .withdraw-thumbnail {
        max-width: 220px;
        max-height: 220px
    }
</style>
@endpush