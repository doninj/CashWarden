<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     @OA\Property (
 *         property="amount", type="number", example="2000.0"
 *     ),
 *     @OA\Property (
 *         property="previsionDate", type="date", example="2022-03-09"
 *     ),
 * )
class UpdateLimitedBudgetRequest extends FormRequest
{
    private $amountMessage = [
        "amount.required" => "Merci de renseigner un montant !",
        "amount.numeric" => "Le format du montant doit être du type numérique !",
        "amount.between" => "Le montant doit avoir une valeur entre 0 et 999 999 999 999.99"
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
        return parent::messages() + $this->amountMessage;
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
        ];
    }
}
