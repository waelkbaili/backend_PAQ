<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Chat extends Model
{
    use HasFactory;
    protected $fillable=[
        'message',
        'type',
        'user_id'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
