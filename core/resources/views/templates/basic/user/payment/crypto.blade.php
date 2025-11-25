@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="add-money-card style--two">
                    <h4 class="title"><i class="lar la-file-alt"></i> @lang('Summery')</h4>
                    <div class="add-moeny-card-middle">
                        <ul class="add-money-details-list">
                            <li>
                                <span class="caption">@lang('PLEASE SEND EXACTLY') </span>
                                <div class="value">
                                    <span class="show-amount">
                                        {{ $data->amount}}</span> {{__($data->currency)}}
                                </div>
                            </li>
                            <li>
                                <span class="caption">@lang('TO')</span>
                                <div class="value">
                                    <span class="text-success"> {{ $data->sendto }}</span>
                                </div>
                            </li>
                            <li>
                                <img src="{{$data->img}}" alt="@lang('Image')">
                            </li>
                            <li>
                                <h4 class="text-white bold my-4">@lang('SCAN TO SEND')</h4>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection