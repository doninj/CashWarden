<?php

namespace App\Http\Controllers\Authentification;

use App\Http\Requests\Authentification\LoginRequest;
use App\Http\Requests\Authentification\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="User auth",
 *      description="Toutes les requêtes d'authentification",
 * )
 */
class UserAuthController
{

    /**
     *@OA\Tag(name="Authentification", description="Requêtes d'authentifications")
     */

    /**
     *      @OA\Examples(
     *        summary="RegisterRequest",
     *        example = "RegisterRequest",
     *        value = {
     *          "lastName": "GIMENEZ",
     *          "firstName": "Tim",
     *          "email": "tim.gimenez26@ynov.com",
     *          "id": 2,
     *        },
     *      )
     */

    /**
     *
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Authentification"},
     *      summary="Permet de s'enregistrer",
     *      description="Crée un utilisateur",
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/RegisterRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Content",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="firstName",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un prénom !"
     *                          ),
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="lastName",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un nom !"
     *                          ),
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un email !"
     *                          ),
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un mot de passe !"
     *                          ),
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="passwordConfirmation",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un mot de passe !"
     *                          ),
     *                      ),
     *                  )
     *             ),
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="An example resource",
     *         @OA\JsonContent(
     *             type="object",
     *                 @OA\Property (
     *                     property="id", type="integer", example="1"
     *                 ),
     *                 @OA\Property (
     *                     property="lastname", type="string", example="DUPONT"
     *                 ),
     *                 @OA\Property (
     *                     property="firstname", type="string", example="Jean"
     *                 ),
     *                 @OA\Property (
     *                     property="email", type="string", example="dupont.jean@gmail.com"
     *                 ),
     *                 @OA\Property (
     *                     property="password", type="string", example="tuturu"
     *                 )
     *         )
     *     ),
     * )
     *
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
     *
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
     * Méthode permettant de récupérer une liste des mois et années années de chaque transaction
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
     *
     * @OA\Post(
     *      path="/api/login",
     *      operationId="login",
     *      tags={"Authentification"},
     *      summary="Permet de se connecter",
     *      description="Connecte l'utilisateur",
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/LoginRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Content",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un email !"
     *                          ),
     *                      ),
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              type="string",
     *                              example="Merci de renseigner un mot de passe !"
     *                          ),
     *                      ),
     *                  )
     *             ),
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="An example resource",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 type="array",
     *                 @OA\Items(
     *                         @OA\Property (
     *                             property="id", type="integer", example="1"
     *                         ),
     *                         @OA\Property (
     *                             property="lastname", type="string", example="DUPONT"
     *                         ),
     *                         @OA\Property (
     *                             property="firstname", type="string", example="Jean"
     *                         ),
     *                         @OA\Property (
     *                             property="email", type="string", example="dupont.jean@gmail.com"
     *                         ),
     *                         @OA\Property (
     *                             property="password", type="string", example="tuturu"
     *                         ),
     *                         @OA\Property (
     *                             property="hasBankAuthorization", type="boolean", example="false"
     *                         ),
     *                         @OA\Property (
     *                             property="hasAccountChoices", type="boolean", example="false"
     *                         )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZmFmOTQ5ODlkMDkzNjA1YTM1MGIxN2I3Yzg0ZDUyNTRlZTdmZmIwMGU5NjBiNWMzMDhjM2YwZTIyNmQ1MDFhNGI2N2Y0YjAwYTQzOGY3M2IiLCJpYXQiOjE2NDc0Njc4MDMuODE4NTMyLCJuYmYiOjE2NDc0Njc4MDMuODE4NTMzLCJleHAiOjE2NzkwMDM4MDMuODExNDA1LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.ZdiWzTvqKzMgvq8Uww6NwDfCIWH0LqoX7llgU6DCVdeHPV7kemgDlP7KEzdxQk-I0YyJYNiNbPcFqsEvQTAN4C4bPcp47UoKub3AVfXCDT7noDEiwfBQV602BLnwsQiH-q1oYUE54zjtO1hOvud2ESYCv7m5iinPoL9ALvVOd184ZTOb3KNLeo6eHx6AJ5XUOXwOIs6lB6efMeeKx3PSfY8xikNu_KjwMpC9YRXEo9XSOzuwqByzBzWJ70p5DWLF6MqtNA2SgT0UxiOUZWDu8XwxpGjiwZ2GUZubX95xICgnbejnXGLPThLZLDVXYb6BCrbI3GgjEQyQf6yP3vybI9AfSeW6r-6nP7QnZ8bTDuLqFEXEdbr4Os7LrTGFhiYDu-NrmFCKNGPaaLNwhpcjFCWLAGjPW24r4lQB2NGX9763fuw0XEdCUvFVKS7ER9LtOPVO7_TUzUWbn8O7TxlLLDdOFcicvX4r2Z8C44COcVQZQh7S9DmOU2y6r3au46ReQyqNpZpzWTfeYFnxG6PjUg6t6cBRGUUXUUuqL3H4ftVhPFjWHMk7cbFsVMlXyILu7GhB6g5Mm3epUVKsu9U0OpP-dJqovyMkpTMJpkBzji1omokj4ILRGNzoFeGEIU0lGEfmVJ55D_nHca0plqBcmdbhwkBNp2DIy4nCT48a_qI"
     *             )
     *         )
     *     )
     * )
     *
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
     *
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logout",
     *      tags={"Authentification"},
     *      summary="Permet de se connecter",
     *      description="Déconnecte l'utilisateur",
     *      security={
     *          {"bearer": {}}
     *      },
     *
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Unauthenticated."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="An example resource",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message", type="string", example="Vous avez été déconnecté"
     *             )
     *         )
     *     )
     * )
     *
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
