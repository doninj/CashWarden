<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    /**
     * Messages d'erreur concernant le nom de l'utilisateur
     * @var array
     */
    private $lastNameMessage = [
        "lastName.max" => "Le nom est trop long ! Celui-ci doit faire moins de 50 caractères !",
    ];

    /**
     * Messages d'erreur concernant le prénom de l'utilisateur
     * @var array
     */
    private $firstNameMessage = [
        "firstName.max" => "Le prénom est trop long ! Celui-ci doit faire moins de 50 caractères !",
    ];

    /**
     * Messages d'erreur concernant le mot de passe de l'utilisateur
     * @var array
     */
    private $passwordMessage = [
        "password.max" => "Votre mot de passe est trop long !",
    ];

    /**
     * Messages d'erreur concernant le mot de passe de l'utilisateur
     * @var array
     */
    private $accountIdMessage = [
        "account.id" => "Votre mot de passe est trop long !",
    ];

    /**
     * Messages d'erreur concernant le mot de passe de l'utilisateur
     * @var array
     */
    private $accountNameMessage = [
        "account.name.max" => "Votre mot de passe est trop long !",
        "account.name.string" => "Votre mot de passe est trop long !",
    ];

    /**
     * Messages d'erreur concernant l'id de message
     * @var array
     */
    private $bankIdMessage = [
        "bank_id.integer" => "L'identifiant de la banque doit être du type entier",
        "bank_id.exists" => "L'identifiant de la banque n'existe pas !"
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
            $this->lastNameMessage +
            $this->firstNameMessage +
            $this->passwordMessage +
            $this->bankIdMessage +
            $this->accountIdMessage +
            $this->accountNameMessage;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "idRequisition" => "max:128",
            "lastName" => "max:50",
            "firstName" => "max:50",
            "password" => "confirmed",
            "bank_id" => "integer|exists:banks,id",
            "account" => "array|size:2",
            "account.id" => "required_with:account",
            "account.name" => "required_with:account|max:255|string",
        ];
    }
}
