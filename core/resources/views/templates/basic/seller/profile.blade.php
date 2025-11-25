@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="seller-profile">
                    <div class="seller-profile__thumb">
                        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }}"
                            alt="image">
                    </div>
                    <div class="seller-profile__left-content">
                        <h4 class="name">{{ __($user->fullname) }}<i class="fas fa-check-circle text--success"></i>
                        </h4>

                        <p class="mt-1">{{ count($user->transactions) }} @lang('Transactions totalling')
                            {{$general->cur_sym }}{{ showAmount($user->transactions->sum('amount')) }}
                        </p>
                        <ul class="seller-profile__info-list d-flex flex-wrap align-items-center">
                            <li>@lang('Location:') {{ __($user->address->country) }}</li>
                            <li>@lang('Memeber since:') {{ diffForHumans($user->created_at) }}</li>
                        </ul>
                    </div>
                    <div class="seller-profile__middle-content">
                        <p><i class="far fa-comments text--base me-1"></i> 32 comments placed</p>
                        <p><i class="fas fa-gavel text--base me-1"></i> 0 bids placed</p>
                        <p><i class="far fa-list-alt text--base me-1"></i> 12 listings placed</p>
                        <p><i class="fas fa-hand-holding-heart text--base me-1"></i> 6 listings sold</p>
                    </div>
                    <div class="seller-profile__right-content">
                        <p class="d-flex flex-wrap align-items-center fs--16px"><i
                                class="far fa-check-circle text--success me-2"></i> @lang('Full name')</p>
                        <p class="d-flex flex-wrap align-items-center fs--16px"><i
                                class="far fa-check-circle text--success me-2"></i> @lang('Email address')</p>
                        <p class="d-flex flex-wrap align-items-center fs--16px"><i
                                class="far fa-check-circle text--success me-2"></i> @lang('Phone Number')</p>
                    </div>
                </div><!-- seller-profile end -->
            </div>
            <div class="col-lg-12 mt-5">
                <div class="custom--card">
                    <div class="card-header border-bottom-0">
                        <h6><i class="las la-clipboard-list"></i> @lang('Domain Lists')</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Domain')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Total Bids')</th>
                                        <th>@lang('End Date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->domains as $item)
                                    <tr>
                                        <td data-label="@lang('Domain')">
                                            <div class="table-website">
                                                <div class="table-website__content">
                                                    <h6 class="title fs--16px">
                                                        <a href="http://{{ __($item->name) }}">
                                                            {{ __($item->name) }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Price')">
                                            {{ showAmount($item->price) }}&nbsp;{{ $general->cur_text }}
                                        </td>
                                        <td data-label="@lang('Total Bids')">30</td>
                                        <td data-label="@lang('End Date')">
                                            <span data-countdown="{{ $item->end_time }}"
                                                data-title="@lang('The auction end')">
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan=" 100%" class="text-center">{{ __($emptyMessage) }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- custom--card end -->
            </div>
        </div>
    </div>
</div>
@endsection