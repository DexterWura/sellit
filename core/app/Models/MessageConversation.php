<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageConversation extends Model {
    use HasFactory;
    protected $guarded = [];
    public function messages() {
        return $this->hasMany(Message::class, 'conversation_id');
    }
    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function domain() {
        return $this->belongsTo(Domain::class, 'domain_id');
    }
}
