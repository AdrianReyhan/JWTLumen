<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'comment',
        'post_id',
        'user_id',
    ];
    public function post()
    {
        return $this->belongsTo(Post::class);  
    }

    public function user()
    {
        return $this->belongsTo(User::class);  
    }
}
