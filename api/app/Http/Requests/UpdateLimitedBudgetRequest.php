<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLimitedBudgetRequest extends FormRequest
{
    private $amountMessage = [
        "amount.required" => "Merci de renseigner un montant !",
        "amount.numeric" => "Le format du montant doit être du type numérique !",
        "amount.between" => "Le montant doit avoir une valeur entre 0 et 999 999 999 999.99"
    ];

    private $previsionDateMessage = [
        "previsionDate.required" => "Merci de renseigner une date prévisionnel de limite de budget !",
        "previsionDate.date_format" => "La date prévisionnelle doit être au format YYYY-MM-JJ"
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

    public function messages()
    {
        return parent::messages() +
            $this->amountMessage +
            $this->previsionDateMessage;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "amount" => "numeric|between:0,999999999999.99",
            "previsionDate" => "date_format:Y-m-d"
        ];
    }
}
