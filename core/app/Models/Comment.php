<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function domain() {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
