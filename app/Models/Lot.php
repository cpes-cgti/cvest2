<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Corrector;
use App\Models\Redaction;

class Lot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status', 'corrector_id'
    ];

    /* Relacionamentos N:1 */
    public function corrector()
	{
		return $this->belongsTo('App\Models\Corrector');
    }

    /* Relacionamentos N:N */
    public function redactions()
	{
		return $this->belongsToMany('App\Models\Redaction')->withTimestamps();
    }

}
