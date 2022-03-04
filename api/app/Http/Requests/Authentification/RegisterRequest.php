<?php

namespace App\Http\Requests\Authentification;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    /**
     * Messages d'erreur concernant le nom de l'utilisateur
     * @var array
     */
    private $lastNameMessage = [
        "lastName.required" => "Merci de renseigner un nom !",
        "lastName.max" => "Le nom est trop long ! Celui-ci doit faire moins de 50 caractères !",
    ];

    /**
     * Messages d'erreur concernant le prénom de l'utilisateur
     * @var array
     */
    private $firstNameMessage = [
        "firstName.required" => "Merci de renseigner un prénom !",
        "firstName.max" => "Le prénom est trop long ! Celui-ci doit faire moins de 50 caractères !",
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

    private $avatarMessage = [
        'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=50,min_height=50,max_width=2000,max_height=2000',
        "avatar.required" => "Merci de renseigner une image !",
        "avatar.image" => "Merci de bien sélectionner une image",
        "avatar.mimes" => "L'image doit être au format jpg, png, jpeg, gif ou svg",
        "avatar.dimensions" => "L'image doit avoir une taille entre 50x50 & 2000x2000"
    ];

    /**
     * Détermine si l'utilisateur est autorisé à appelé la requête
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
            $this->firstNameMessage +
            $this->lastNameMessage +
            $this->avatarMessage +
            $this->emailMessage +
            $this->passwordMessage;
    }

    /**
     * Récupère les règles de validations à appliquer à la requête
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => 'required|max:50',
            'lastName' => 'required|max:50',
            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=50,min_height=50,max_width=2000,max_height=2000',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ];
    }
}
