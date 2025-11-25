<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                </a>

                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>

                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">

                        <li><a href="{{ route('home') }}">@lang('Home')</a></li>

                        <li><a href="{{ route('domain.list') }}">@lang('Domains')</a></li>

                        @if(request()->routeIs('user.*'))
                        <li class="menu_has_children">
                            <a href="#0">@lang('Deposit')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.deposit') }}">@lang('Deposit Money')</a></li>
                                <li><a href="{{ route('user.deposit.history') }}">@lang('My Deposits')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children">
                            <a href="#0">@lang('Withdraw')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.withdraw') }}">@lang('Withdraw Money')</a></li>
                                <li><a href="{{ route('user.withdraw.history') }}">@lang('My Withdrawals')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children">
                            <a href="#0">@lang('Auction')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.auction.create') }}">@lang('Create Auction')</a></li>
                                <li><a href="{{ route('user.auction.list') }}">@lang('My Auctions')</a></li>
                                <li><a href="{{ route('user.bids.history') }}">@lang('My Bids')</a></li>
                                <li><a href="{{ route('user.bids.winner') }}">@lang('Bid Winners')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children">
                            <a href="#0">@lang('Account')</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('user.transaction') }}">@lang('Transactions')</a></li>
                                <li><a href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a></li>
                                <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                                <li><a href="{{ route('ticket.open') }}">@lang('Open New Ticket')</a></li>
                                <li><a href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
                                <li><a href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                            </ul>
                        </li>


                        @else
                            @foreach($pages as $k => $data)
                            <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                            @endforeach
                            <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                            @guest
                            <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                            @endguest
                        @endif




                    <div class="nav-right justify-content-xl-end">
                        <button type="button" class="header__search-btn"><i class="las la-search"></i></button>
                        @if($language->count())
                        <select class="header-lang langSel">
                            @foreach($language as $item)
                            <option value="{{$item->code}}" @if(session('lang')==$item->code) selected @endif>{{
                                __($item->name) }}</option>
                            @endforeach
                        </select>
                        @endif
                        <form class="header-search-form" action="{{ route('domain.list') }}" method="GET">
                            <input type="text" name="search" autocomplete="off" class="form--control"
                                placeholder="@lang('e.g. example.com')">
                            <button type="submit" class="header-search-form__btn"><i class="las la-search"></i></button>
                        </form>
                        @guest
                        <div class="btn--group style--two">
                            <a href="{{ route('user.register') }}" class="btn btn-sm btn-outline--base">@lang('Sign Up')</a>
                            <a href="{{ route('user.login') }}" class="btn btn-sm btn--base">@lang('Login')</a>
                        </div>
                        @else
                        <div class="btn--group style--two">
                            <a href="{{ route('user.home') }}" class="btn btn-sm btn--base">@lang('Dashboard')</a>
                        </div>
                        @endguest
                    </div>

                </div>
            </nav>
        </div>
    </div>
</header>
