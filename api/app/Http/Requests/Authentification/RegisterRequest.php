<?php

namespace App\Http\Requests\Authentification;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     @OA\Property (
 *         property="lastname", type="string", example="DUPONT"
 *     ),
 *     @OA\Property (
 *         property="firstname", type="string", example="Jean"
 *     ),
 *     @OA\Property (
 *         property="email", type="string", example="dupont.jean@gmail.com"
 *     ),
 *     @OA\Property (
 *         property="password", type="string", example="tuturu"
 *     ),
 *     @OA\Property (
 *         property="passwordConfirmation", type="string", example="tuturu"
 *     )
 * )
 */
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
     *
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
     * Messages d'erreur contenant les vérification de mot de passe
     * @var string[]
     */
    private $passwordConfirmationMessage = [
        "passwordConfirmation.required" => "Merci de confirmer votre mot de passe !",
        "passwordConfirmation.same" => "Les mots de passe ne correspondent pas !",
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
            $this->emailMessage +
            $this->passwordMessage +
            $this->passwordConfirmationMessage;
    }

    /**
     * @OA\Property(format="string", default="DUPONT", description="last name", property="lastname"),
     * @OA\Property(format="string", default="Jean", description="first name", property="firstname"),
     * @OA\Property(format="string", default="dupont.jean@gmail.com", description="email", property="email"),
     * @OA\Property(format="string", default="password", description="password", property="password"),
     * @OA\Property(format="string", default="password", description="password confirmation", property="passwordConfirmation"),
     */

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
            'email' => 'required|email|unique:users',
            'password' => 'required|required_with:passwordConfirmation',
            'passwordConfirmation' => 'required|same:password',
        ];
    }
}
