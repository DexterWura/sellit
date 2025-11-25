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
                                <th>@lang('S.N.')</th>
                                <th>@lang('Listing')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Total Bids')</th>
                                <th>@lang('End Date')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($domains as $domain)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $loop->index + $domains->firstItem() }}</td>
                                <td data-label="@lang('Listing')">
                                    <span class="name">{{ __($domain->display_name) }}</span><br>
                                    <a href="{{ route('admin.users.detail', @$domain->user->id) }}">{{
                                        __(@$domain->user->username) }}</a>
                                    @if(!$domain->is_verified)
                                        <br><small class="text-danger">@lang('Not Verified')</small>
                                    @endif
                                </td>
                                <td data-label="@lang('Type')">
                                    <span class="badge badge--{{ $domain->listing_type === 'domain' ? 'primary' : ($domain->listing_type === 'website' ? 'success' : 'info') }}">
                                        {{ __($domain->listing_type_name) }}
                                    </span>
                                </td>
                                <td data-label="@lang('Price')">
                                    <div class="user">
                                        <span class="name">{{ __(showAmount($domain->price)) }}&nbsp;{{
                                            $general->cur_text
                                            }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Total Bids')">
                                    <div class="user">
                                        <span class="name font-weight-bold">{{ $domain->bids_count }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('End Date')">
                                    <div class="user">
                                        <span class="name">{{ diffForHumans($domain->end_time) }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('Status')">
                                    @php echo $domain->statusText @endphp
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.domain.view',$domain->id) }}" class="icon-btn"
                                        data-toggle="tooltip" title="" data-original-title="@lang('View')">
                                        <i class="la la-desktop"></i>
                                    </a>

                                    <a href="{{ route('admin.domain.bids',$domain->id) }}" class="icon-btn btn--info ml-1"
                                        data-toggle="tooltip" title="" data-original-title="@lang('View Bids')">
                                        <i class="la la-hammer"></i>
                                    </a>
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
            <div class="card-footer py-4">
                {{ paginateLinks($domains) }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<form method="GET" class="form-inline float-sm-right search-form w-sm-auto w-unset bg-white">
    <div class="input-group has_append">
        <select name="listing_type" class="form-control mr-2" style="width: auto;">
            <option value="">@lang('All Types')</option>
            @foreach($listingTypes ?? [] as $key => $value)
                <option value="{{ $key }}" @if(request()->listing_type == $key) selected @endif>{{ __($value) }}</option>
            @endforeach
        </select>
        <input type="text" name="search" id="mySearch" class="form-control" placeholder="@lang('Search listings...')"
            value="{{ request()->search }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>

@endpush
