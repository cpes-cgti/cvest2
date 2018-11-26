<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Corrector extends Model
{
    protected $fillable = [
        'cpf', 'siape','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
