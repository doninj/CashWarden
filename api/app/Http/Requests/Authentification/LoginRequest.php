<?php

namespace App\Http\Requests\Authentification;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    /**
     * Messages d'erreur concernant le nom de l'utilisateur
     * @var array
     */
    private $lastNameMessage = [
        "lastName.required" => "Merci de renseigner un nom !",
        "lastName.max" => "Le nom est trop long !",
    ];

    /**
     * Messages d'erreur concernant le prénom de l'utilisateur
     * @var array
     */
    private $firstNameMessage = [
        "firstName.required" => "Merci de renseigner un prénom !",
        "firstName.max" => "Le prénom est trop long !",
    ];

    /**
     * Messages d'erreur concernant l'email de l'utilisateur
     * @var array
     */
    private $emailMessage = [
        "email.required" => "Merci de renseigner un email !",
        "email.email" => "Merci de renseigner un email valide !",
        "email.unique" => "Cet email est déjà utilisé !",
    ];

    /**
     * Messages d'erreur concernant le mot de passe de l'utilisateur
     * @var array
     */
    private $passwordMessage = [
        "password.required" => "Merci de renseigner un mot de passe !",
        "password.unique" => "Le mot de passe existe déjà !",
        "password.max" => "Votre mot de passe est trop long !",
    ];

    /**
     * Messages d'erreur concernant le compte de l'utilisateur
     * @var array
     */
    private $accountIdMessage = [
        "account_id.integer" => "Merci de renseigner un role !"
    ];

    /**
     * Détermine si l'utilisateur est authorisé à lancer la requête
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return parent::messages() + $this->emailMessage + $this->passwordMessage;
    }

    /**
     * Récupère les règles de validations à appliquer à la requête
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ];
    }
}
