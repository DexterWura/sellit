@php
use App\Models\Domain;
use App\Enums\ListingType;
use Illuminate\Support\Facades\Log;

try {
    $listingsSection = getContent('listings.content', true);
    $showCount = isset($listingsSection->data_values->show_count) && !empty($listingsSection->data_values->show_count) 
        ? (int)$listingsSection->data_values->show_count 
        : 12;

    // Get featured listings
    $featuredListings = Domain::active();
    
    // Only filter by is_verified if column exists
    if (\Illuminate\Support\Facades\Schema::hasColumn('domain_posts', 'is_verified')) {
        $featuredListings = $featuredListings->where('is_verified', true);
    }
    
    $featuredListings = $featuredListings
        ->with('category', 'user')
        ->withCount('bids')
        ->latest()
        ->take($showCount)
        ->get();
} catch (\Exception $e) {
    Log::error('Listings section error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    $featuredListings = collect([]);
    $listingsSection = (object)['data_values' => (object)['heading' => 'Online Businesses For Sale', 'subheading' => 'Browse verified online businesses']];
}
@endphp

<section class="listings-section pt-100 pb-100 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-header">
                    <h2 class="section-title">{{ __($listingsSection->data_values->heading ?? 'Online Businesses For Sale') }}</h2>
                    <p class="section-subtitle">{{ __($listingsSection->data_values->subheading ?? 'Browse verified online businesses, websites, and digital assets') }}</p>
                </div>
            </div>
        </div>

        @if($featuredListings->count() > 0)
        <div class="row g-4 listings-grid">
            @foreach($featuredListings as $listing)
            <div class="col-lg-4 col-md-6 listing-card-wrapper">
                <div class="listing-card" data-listing-type="{{ $listing->listing_type }}">
                    <div class="listing-card__image">
                        @php
                            $thumbnailPath = null;
                            if ($listing->thumbnail) {
                                $thumbnailPath = imagePath()['listings']['thumbnails']['path'] . '/' . $listing->thumbnail;
                            } elseif ($listing->images && is_array($listing->images) && count($listing->images) > 0) {
                                $thumbnailPath = imagePath()['listings']['images']['path'] . '/' . $listing->images[0];
                            }
                        @endphp
                        @if($thumbnailPath)
                            @php
                                $fullPath = public_path($thumbnailPath);
                                $imageExists = file_exists($fullPath);
                            @endphp
                            @if($imageExists)
                                <img src="{{ asset($thumbnailPath) }}" alt="{{ $listing->display_name }}" class="listing-thumbnail" loading="lazy">
                            @else
                                <div class="listing-thumbnail-placeholder">
                                    <i class="fas fa-{{ $listing->listing_type === 'domain' ? 'globe' : ($listing->listing_type === 'website' ? 'desktop' : 'share-alt') }}"></i>
                                </div>
                            @endif
                        @else
                            <div class="listing-thumbnail-placeholder">
                                <i class="fas fa-{{ $listing->listing_type === 'domain' ? 'globe' : ($listing->listing_type === 'website' ? 'desktop' : 'share-alt') }}"></i>
                            </div>
                        @endif
                        <div class="listing-card__overlay">
                            <div class="listing-card__badges">
                                <span class="listing-badge listing-badge--{{ $listing->listing_type === 'domain' ? 'primary' : ($listing->listing_type === 'website' ? 'success' : 'info') }}">
                                    {{ __($listing->listing_type_name) }}
                                </span>
                                @if($listing->is_verified)
                                <span class="listing-badge listing-badge--verified">
                                    <i class="fas fa-check-circle"></i> @lang('Verified')
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="listing-card__actions">
                            <a href="{{ route('domain.detail', ['id' => $listing->id, 'name' => slug($listing->name)]) }}" class="btn btn-sm btn--base">
                                @lang('View Details')
                            </a>
                        </div>
                    </div>
                    <div class="listing-card__content">
                        <h3 class="listing-card__title">
                            <a href="{{ route('domain.detail', ['id' => $listing->id, 'name' => slug($listing->name)]) }}">
                                {{ __($listing->display_name) }}
                            </a>
                        </h3>
                        <div class="listing-card__meta">
                            <div class="listing-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ __($listing->location) }}</span>
                            </div>
                            <div class="listing-meta-item">
                                <i class="fas fa-chart-line"></i>
                                <span>{{ number_format($listing->traffic) }} @lang('Traffic')</span>
                            </div>
                        </div>
                        <div class="listing-card__footer">
                            <div class="listing-price">
                                <span class="price-label">@lang('Starting at')</span>
                                <span class="price-value">{{ $general->cur_sym }}{{ showAmount($listing->price) }}</span>
                            </div>
                            <div class="listing-stats">
                                <span class="stat-item">
                                    <i class="fas fa-gavel"></i>
                                    {{ $listing->bids_count }} @lang('Bids')
                                </span>
                                @if($listing->end_time)
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    <span class="countdown-timer" data-countdown="{{ $listing->end_time }}"></span>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-5">
            <div class="col-lg-12 text-center">
                <a href="{{ route('domain.list') }}" class="btn btn--base btn-lg">
                    @lang('Browse All Listings') <i class="las la-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="empty-listing">
                    <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                    <p class="text-muted">@lang('No listings available at the moment')</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@push('script')
<script>
    'use strict';
    (function($) {
        // Initialize countdown timers
        $('.countdown-timer').each(function() {
            var $this = $(this);
            var endTime = $this.data('countdown');
            
            if (endTime) {
                $this.countdown(endTime, function(event) {
                    var format = '%D:@lang("d") %H:@lang("h") %M:@lang("m")';
                    if (event.offset.days === 0) {
                        format = '%H:@lang("h") %M:@lang("m") %S:@lang("s")';
                    }
                    $this.html(event.strftime(format));
                });
            }
        });

        // Add intersection observer for fade-in animations
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.listing-card-wrapper').forEach(card => {
                observer.observe(card);
            });
        }
    })(jQuery);
</script>
@endpush

