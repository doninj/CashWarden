<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequisitionRequest extends FormRequest
{
    /**
     * Messages d'erreurs bank_id
     * @var string[]
     */
    private $messageBankId = [
        "bank_id.required" => "L'identifiant de la banque doit être renseigné",
        "bank_id.string" => "Le type doit être de type texte !",
        "bank_id.exists" => "la banque choisie n'est pas prise en charge ou n'existe tout simplement pas",
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "bank_id" => "required|string|exists:banks,id"
        ];
    }

    public function messages()
    {
        return parent::messages() +
            $this->messageBankId;
    }
}
