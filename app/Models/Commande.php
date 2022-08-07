<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Commande extends Model
{
    use HasFactory;

    protected $fillable=[
        'listCmd',
        'cmdRate',
        'note',
        'address',
        'client_id',
        'cuistot_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
