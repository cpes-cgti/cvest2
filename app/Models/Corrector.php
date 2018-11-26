<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Corrector extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'cpf', 'siape','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
