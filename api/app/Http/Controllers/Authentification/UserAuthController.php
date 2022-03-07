<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Requests\Authentification\LoginRequest;
use App\Http\Requests\Authentification\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserAuthController
{
    /**
     * Méthode permettant de s'enregistrer
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        // Création de l'utilisateur
        $validated = $request->validated();
        $user = new User();
        $user->lastName = $validated["lastName"];
        $user->firstName = $validated["firstName"];
        $user->email = $validated["email"];
        $user->setPassword($validated["password"]);

        $user->save();

        $token = $user->createToken('API Token')->accessToken;

        return response([
            "user" => $user,
            'token' => $token
        ]);
    }

    /**
     * Méthode récupérant un utilisateur grâce aux données d'une requête
     *
     * @param $data
     * @return mixed
     */
    private function getUserThanksToRequestData($data)
    {
        return User::with('account')->where('email', $data["email"])->first();
    }

    /**
     * Méthode permettant de se connecter
     *
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {

        //On récupère les données valides
        $data = $request->validated();

        // On rècupère l'utilisateur en utilisant l'email
        $user = $this->getUserThanksToRequestData($data);

        //On check les mots de passe
        if (Hash::check($data["password"], $user->password)) {

            //On génère le token
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;

            //On retourne la réponse en utilisant le token
            $response = [
                'user' => [
                    "id" => $user->id,
                    "firstName" => $user->firstName,
                    "lastName" => $user->lastName,
                    "hasBankAuthorization" => $user->hasBankAuthorization,
                    "hasAccountChoices" => $user->hasAccountChoices,
                    "avatar" => $user->avatar,
                    "account" => $user->account
                ],
                'token' => $token
            ];
            return response($response, 200);
        } else {
            $response = ["message" => "Les informations de connexion sont incorrects !"];
            return response($response, 422);
        }
    }

    /**
     * Méthode permettant de se déconnecter
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // récupère le token
        $token = $request->user()->token();
        // Supprime le token
        $token->revoke();

        // Retour de la réponse de succès
        $response = ['message' => 'Vous avez été déconnecté'];
        return response($response, 200);
    }
}
