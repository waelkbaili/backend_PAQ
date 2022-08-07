<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plat;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable=[
        'createur',
        'commentaire',
        'plat_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function plat(){
        return $this->belongsTo(Plat::class);
    }
}
