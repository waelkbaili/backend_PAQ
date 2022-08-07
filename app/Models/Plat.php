<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Commentaire;

class Plat extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'description',
        'plat_number',
        'price',
        'type',
        'image',
        'link',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function commentaires(){
        return $this->hasMany(Commentaire::class);
    }
}
