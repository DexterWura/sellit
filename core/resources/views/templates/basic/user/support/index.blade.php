@extends($activeTemplate.'layouts.frontend')

@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Subject')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Priority')</th>
                                        <th>@lang('Last Reply')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supports as $key => $support)
                                    <tr>
                                        <td data-label="@lang('Subject')">
                                            <a href="{{ route('ticket.view', $support->ticket) }}">[@lang('Ticket')#{{
                                                $support->ticket }}]</a>
                                            <span>{{__($support->subject) }}</span>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if($support->status == 0)
                                            <span class="badge badge--success">@lang('Open')</span>
                                            @elseif($support->status == 1)
                                            <span class="badge badge--primary">@lang('Answered')</span>
                                            @elseif($support->status == 2)
                                            <span class="badge badge--warning">@lang('Customer Reply')</span>
                                            @elseif($support->status == 3)
                                            <span class="badge badge--dark">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Priority')">
                                            @if($support->priority == 1)
                                            <span class="badge badge--dark">@lang('Low')</span>
                                            @elseif($support->priority == 2)
                                            <span class="badge badge--success">@lang('Medium')</span>
                                            @elseif($support->priority == 3)
                                            <span class="badge badge--primary">@lang('High')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Last Reply')">
                                            {{\Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('ticket.view', $support->ticket) }}"
                                                class="icon-btn bg--base" data-bs-toggle="tooltip"
                                                data-bs-position="top" title="Ticket View"><i
                                                    class="las la-desktop"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{$supports->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
