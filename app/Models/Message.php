<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_id', 'text','sender','file'];

    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }
}
