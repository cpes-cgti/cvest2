<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cpf implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $cpf = $value;
        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina tudo que não for um dígito [0-9] e completa com zeros a esquerda
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            return false;
        // Calcula os digitos verificadores para verificar se o
        // CPF é válido
        } else {   
            /* Calcula e valida o primeiro dígito verificador */
            $s = 0;
            for ($i=0, $m=10;$i<9;$i++, $m--){ 
                $s += $cpf[$i] * $m;
            }
            $resto = $s % 11;
            $dv1 = $resto < 2 ? 0 : 11 - $resto;
            if ($dv1 != $cpf[9]) return false;
            /* Calcula e valida o segundo dígito verificador */
            $s = 0;
            for ($i=0, $m=11;$i<10;$i++, $m--){
                $s += $cpf[$i] * $m;
            }
            $resto = $s % 11;
            $dv2 = $resto < 2 ? 0 : 11 - $resto;
            if ($dv2 != $cpf[10]) return false;

            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O CPF informado não é válido.';
    }
}
