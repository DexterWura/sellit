@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Transaction ID')</th>
                                        <th>@lang('Payment Type')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Post Balance')</th>
                                        <th>@lang('Time')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $k=>$data)
                                    <tr>
                                        <td data-label="#@lang('Trx')">{{ $data->trx }}</td>
                                        <td data-label="@lang('Payment Type')">{{ __($data->details) }}</td>
                                        <td data-label="@lang('Amount')">
                                            @if ($data->trx_type == '-')
                                            <b class="text--danger">{{ __($data->trx_type) }}&nbsp;{{
                                                $general->cur_sym }}{{ showAmount($data->amount) }}</b>
                                            @else
                                            <b class="text--success">{{ __($data->trx_type) }}&nbsp;{{
                                                $general->cur_sym }}{{ showAmount($data->amount) }}</b>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Post Balance')" class="text-success">
                                            <strong>{{ $general->cur_sym }}{{ showAmount($data->post_balance) }}</strong>
                                        </td>

                                        <td data-label="@lang('Time')">
                                            {{ showDateTime($data->created_at) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="justify-content-center text-center" colspan="100%">
                                            {{ __($emptyMessage) }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                {{$transactions->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
