<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreBankRequest extends FormRequest
{
    /**
     * @var mixed
     */
    private $idBankMessages = [
        "required" => "L'identifiant de la banque est requise !",
        "string" => "L'identifiant de la banque doit être de type string !"
    ];

    /**
     * @var mixed
     */
    private $nameBankMessage = [
        "required" => "Le nom de la banque est requise !",
        "string" => "Le nom de la banque doit être de type string !"
    ];

    /**
     * @var mixed
     */
    private $logoBankMessage = [
        "required" => "Le logo est requis !",
        "string" => "Le logo de la banque doit être de type string !"
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    public function messages()
    {
        return parent::messages() +
            $this->idBankMessages +
            $this->nameBankMessage +
            $this->logoBankMessage;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => "required|string|unique:bank,id",
            "name" => "required|string",
            "logo" => "required|string"
        ];
    }
}
