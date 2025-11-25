@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-10">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title mb-4">@lang('Verify') {{ $domain->listing_type_name }}</h3>
                        
                        @if($domain->is_verified)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> @lang('This listing has been verified successfully!')
                            </div>
                        @else
                            @if($domain->listing_type === 'domain')
                                @include($activeTemplate.'user.auction.verify.domain')
                            @elseif($domain->listing_type === 'website')
                                @include($activeTemplate.'user.auction.verify.website')
                            @elseif($domain->listing_type === 'social_media')
                                @include($activeTemplate.'user.auction.verify.social_media')
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

