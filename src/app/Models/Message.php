<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'user_message',
        'ai_message',
        'num_words',
    ];
    
    public function users()
    {
        return $this->belongsTo(user::class);
    }
}
