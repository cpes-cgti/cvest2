<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Corrector;
use App\User;

class EmailCorrector implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $corrector;
    
    public function __construct($corrector)
    {
        $this->corrector = $corrector;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $usuario = User::where('email', $value)->first();
        if ($usuario == null){
            return true;
        } else {
            $avaliador = Corrector::where('user_id', $usuario->id)->first();
            if ($avaliador == null){
                return true;
            } else {
                if ($avaliador->id == $this->corrector) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Este email já está sendo utilizado por outro avaliador.';
    }
}
