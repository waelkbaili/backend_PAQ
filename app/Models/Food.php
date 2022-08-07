<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Commentaire;

class Food extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'description',
        'plat_number',
        'price',
        'type',
        'image',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function commentaires(){
        return $this->hasMany(Commentaire::class);
    }

}
