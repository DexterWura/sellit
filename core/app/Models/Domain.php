<?php

namespace App\Models;

use App\Enums\ListingType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model {
    use HasFactory;
    protected $table = 'domain_posts';
    protected $guarded = [];

    protected $casts = [
        'analytics_data' => 'array',
        'additional_links' => 'array',
        'images' => 'array',
        'is_verified' => 'boolean',
    ];

    // Accessor methods with error handling
    public function getListingTypeNameAttribute()
    {
        try {
            if (!isset($this->listing_type) || empty($this->listing_type)) {
                return 'Domain';
            }
            $typeValue = $this->listing_type;
            $types = ListingType::all();
            return $types[$typeValue] ?? 'Domain';
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ListingTypeName error', [
                'domain_id' => $this->id ?? null,
                'listing_type' => $this->listing_type ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'Domain';
        }
    }

    public function getDisplayNameAttribute()
    {
        try {
            if ($this->isSocialMedia()) {
                return ($this->social_username ?? '') . ' (' . ucfirst($this->social_platform ?? '') . ')';
            }
            return $this->name ?? 'Untitled';
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('DisplayName error', [
                'domain_id' => $this->id ?? null,
                'message' => $e->getMessage()
            ]);
            return $this->name ?? 'Untitled';
        }
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bids() {
        return $this->hasMany(DomainBid::class, 'domain_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'domain_id');
    }

    public function contactMessages() {
        return $this->hasMany(ContactMessage::class, 'domain_id');
    }

    public function scopeActive() {
        return $this->where('status', 1)->where('end_time', '>=', now()->toDateTimeString());
    }

    public function scopeFinished() {
        return $this->where('status', 1)->where('end_time', '<', now()->toDateTimeString());
    }

    public function scopeSold() {
        return $this->where('status', 2);
    }

    public function scopeByListingType($query, $type) {
        try {
            return $query->where('listing_type', $type);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('scopeByListingType error', [
                'type' => $type,
                'message' => $e->getMessage()
            ]);
            return $query;
        }
    }

    public function isDomain() {
        try {
            if (!isset($this->listing_type) || empty($this->listing_type)) {
                return true; // Default to domain for backward compatibility
            }
            return $this->listing_type === ListingType::DOMAIN;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('isDomain error', [
                'domain_id' => $this->id ?? null,
                'message' => $e->getMessage()
            ]);
            return true; // Default to domain for backward compatibility
        }
    }

    public function isWebsite() {
        try {
            if (!isset($this->listing_type)) {
                return false;
            }
            return $this->listing_type === ListingType::WEBSITE;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('isWebsite error', [
                'domain_id' => $this->id ?? null,
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function isSocialMedia() {
        try {
            if (!isset($this->listing_type)) {
                return false;
            }
            return $this->listing_type === ListingType::SOCIAL_MEDIA;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('isSocialMedia error', [
                'domain_id' => $this->id ?? null,
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getStatusTextAttribute() {
        $class = "badge badge--";

        if ($this->status == 1 && Carbon::parse($this->end_time) >= now()) {
            $class .= 'success';
            $text = 'Approved';
        } elseif ($this->status == 1 && Carbon::parse($this->end_time) < now()) {
            $class .= 'dark';
            $text = 'Finished';
        } elseif ($this->status == 0) {
            $class .= 'warning';
            $text = 'Pending';
        } elseif ($this->status == 2) {
            $class .= 'primary';
            $text = 'Sold';
        }else {
            $class .= 'danger';
            $text = 'Rejected';
        }

        return "<span class='$class'>" . trans($text) . "</span>";
    }

}
