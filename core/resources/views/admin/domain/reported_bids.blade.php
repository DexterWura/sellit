@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Domain Name')</th>
                                <th>@lang('Reported At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bids as $bid)
                            <tr>
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold">{{ @$bid->user->fullname }}</span>
                                    <br>
                                    <span class="small">
                                        <a href="{{ route('admin.users.detail', $bid->user_id) }}"><span>@</span>{{
                                            @$bid->user->username }}</a>
                                    </span>
                                </td>
                                <td data-label="@lang('Domain Name')">
                                    <span class="font-weight-bold">{{ __($bid->domain->name) }}</span>
                                </td>
                                <td data-label="@lang('Reported At')">
                                    {{ showDateTime($bid->reported_at) }}
                                    <br>
                                    {{ $bid->reported_at->diffForHumans() }}
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.domain.bid.conversation.view',$bid->id) }}" class="icon-btn btn--primary">
                                        <i class="fas fa-desktop"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            <div class="card-footer py-4">
                {{ paginateLinks($bids) }}
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection
