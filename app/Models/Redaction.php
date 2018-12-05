<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Corrector;
use App\Models\Lot;

class Redaction extends Model
{

    protected $fillable = [
        'entry', 'file',
    ];

    public function getIdAttribute($value)
    {
        return str_pad($value, 6, "0", STR_PAD_LEFT);
    }

    public function getFinalScoreAttribute($value)
    {
        return is_null($value) ? '' : number_format($value, 2, ",", ".");
    }

    public function laratablesStatus()
    {
        $type = 'default';
        switch ($this->status){
            case 'Digitalizada':
                $type = 'default';
                break;
            case 'Para correção':
                $type = 'warning';
                break;
            case 'Corrigida (1x)':
                $type = 'primary';
                break;
            case 'Corrigida (concluído)':
                $type = 'success';
                break;
            case 'Necessita revisão':
                $type = 'danger';
                break;
        }
        return '<span 
                    style="width: 15em; height: 2em; display: inline-block; line-height: 2em; padding: 0;" 
                    class="label label-' . $type .  '">'. $this->status . 
                '</span>';
    }

    public static function laratablesCustomAction($redaction)
    {
        return view('redactions.actions', compact('redaction'))->render();
    }

    /* Relacionamentos N:N */
    public function correctors()
	{
		return $this->belongsToMany('App\Models\Corrector')->withPivot('score')->withTimestamps();
    }

}
