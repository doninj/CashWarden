<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Requests\Authentification\LoginRequest;
use App\Http\Requests\Authentification\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserAuthController
{
    /**
     * Méthode permettant de s'enregistrer
     *
     * @param RegisterRequest $request
     * @return Application|ResponseFactory|Response
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
        ],201);
    }

    /**
     * Méthode récupérant un utilisateur grâce aux données d'une requête
     *
     * @param $data
     * @return mixed
     */
    private function getUserThanksToRequestData($data)
    {
        $user = User::where('email', $data['email'])->first();

        if ($user->hasAccountChoices) {
            $user->load(['account.GetThreeTransactions',
                'account.transactionsForActualMonth',
                'account.LatestBalances',
                'account.transactions']);

            $collect = collect($user->account->transactionsForActualMonth);
            $user->account['totalSpendingOfActualMonth'] = number_format(abs($collect->map(function ($transaction) {
                if ($transaction->montant < 0)
                    return $transaction->montant;
            })->sum()),2);
            $user->account['totalIncomeOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
                if ($transaction->montant > 0)
                    return $transaction->montant;
            })->sum()),2);
            $this->GetMonthAndYear($user);
        }
        return $user;

    }
    /**
     * Méthode permettant de recuperer les mois et années des transactions
     *
     * @param $userData
     * @return void
     */
    public function GetMonthAndYear($userData) {
        $monthArray = [];
        $yearArray = [];
        $date =  collect($userData->account->transactions);
        foreach ($date as $transaction) {
            $myDate = $transaction->dateTransaction;
            $date = Carbon::createFromFormat('Y-m-d', $myDate);
            $monthName = $date->translatedFormat('F');
            $yearName = $date->translatedFormat('Y');
            if(!in_array($yearName, $yearArray)) {
                array_push($yearArray, $yearName);
            }
            if(!in_array($monthName, $monthArray)) {
                array_push($monthArray, $monthName);
            }
        }
        $userData->account['months'] = $monthArray;
        $userData->account['years'] = $yearArray;
        unset($userData->account->transactions);
    }
    /**
     * Méthode permettant de se connecter
     *
     * @param LoginRequest $request
     * @return Application|ResponseFactory|Response
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
                'user' => $user,
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
     * @return Application|ResponseFactory|Response
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
