@extends('admin.layouts.app')

@section('panel')

<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 my-30">
        <div class="card b-radius--10 ">

            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bids as $bid)
                            <tr>
                                <td data-label="@lang('Domain')">
                                    <a href="{{ route('admin.users.detail', @$bid->user->id) }}">{{
                                        __(@$bid->user->username) }}</a>
                                </td>
                                <td data-label="@lang('Price')">
                                    <div class="user">
                                        <span class="name">
                                            {{ __(showAmount($bid->price)) }} {{$general->cur_text}}
                                        </span>
                                    </div>
                                </td>
                                <td data-label="@lang('Status')">

                                    @if ($bid->win_status == 1)
                                    <span class="badge badge--success">@lang('Win')</span>
                                    @elseif($bid->win_status == 2)
                                    <span class="badge badge--danger">@lang('Lose')</span>
                                    @else
                                    <span class="badge badge--warning">@lang('Pending')</span>
                                    @endif

                                </td>

                                <td data-label="@lang('Action')">

                                    @if ($bid->win_status == 1)
                                        <a href="{{ route('admin.domain.bid.conversation.view',$bid->id) }}" class="icon-btn btn--primary">
                                            <i class="fas fa-desktop"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" class="icon-btn btn--primary disabled">
                                            <i class="fas fa-desktop"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($bids->hasPages())
            <div class="card-footer py-4">
                {{ $bids->links() }}
            </div>
            @endif
        </div>
    </div>
</div>



@endsection

