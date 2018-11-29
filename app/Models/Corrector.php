<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Redaction;
use App\Models\Lot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Corrector extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'cpf', 'siape','user_id'
    ];


    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    /* Relacionamentos 1:N */
    public function lots()
	{
        return $this->hasMany('App\Models\Lot');
    }

    /* Relacionamentos N:N */
    public function redactions()
	{
		return $this->belongsToMany('App\Models\Redaction')->withPivot('score')->withTimestamps();
    }

}
