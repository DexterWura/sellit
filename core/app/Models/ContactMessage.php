<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model {
    use HasFactory;

    protected $guarded = [];

    public function domain() {
        return $this->belongsTo(Domain::class, 'user_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
