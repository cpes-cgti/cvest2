<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Cpf;
use App\Rules\EmailCorrector;

class CorrectorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('level2');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cpf' => ['required', 'digits:11', new Cpf, 'unique:correctors,cpf,' . $this->id ],
            'siape' => 'required|digits:7|unique:correctors,siape,' . $this->id ,
            'name' => 'required|min:3|max:255',
            'email' => ['required', 'email', new EmailCorrector($this->id)],
        ];
    }
}
