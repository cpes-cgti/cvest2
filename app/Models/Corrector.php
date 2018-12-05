<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Redaction;
use App\Models\Lot;

class Corrector extends Model
{

    protected $fillable = [
        'cpf', 'siape','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    /* Relacionamentos N:N */
    public function redactions()
	{
		return $this->belongsToMany('App\Models\Redaction')->withPivot('score')->withTimestamps();
    }

}
