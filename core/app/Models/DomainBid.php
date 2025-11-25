<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainBid extends Model {
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'reported_at' => 'datetime'
    ];

    public function domain() {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bidConversation() {
        return $this->hasMany(BidConversation::class, 'bid_id');
    }


    public function scopePending() {
        return $this->where('status', 0);
    }
    public function scopeCompleted() {
        return $this->where('status', 1);
    }
    public function scopeCredentialGiven() {
        return $this->where('status', 2);
    }
    public function scopeReported() {
        return $this->where('status', 8);
    }
    public function scopeCancel() {
        return $this->where('status', 9);
    }



    public function getStatusTextAttribute()
    {

        $class = "badge badge--";

        if ($this->status == 0) {
            $class .= 'warning';
            $text = 'Pending';
        } elseif ($this->status == 1) {
            $class .= 'success';
            $text = 'Completed';
        } elseif ($this->status == 2) {
            $class .= 'primary';
            $text = 'Credential Given';
        } elseif ($this->status == 8) {
            $class .= 'danger';
            $text = 'Reported';
        } else {
            $class .= 'dark';
            $text = 'Cancelled';
        }

        return "<span class='$class'>" . trans($text) . "</span>";
    }
}
