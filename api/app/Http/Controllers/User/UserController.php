<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        try{
            // Set nom prénom
            $user->firstName = empty($validated['firstName']) ? $user->firstName : $validated["firstName"];
            $user->lastName = empty($validated['lastName']) ? $user->firstName : $validated["lastName"];

            // Set password
            if(!empty($validated['password'])){
                $user->setPassword($validated["password"]);
            }

            if(!empty($validated["bank_id"])){
                $user->setBankById($validated["bank_id"]);
            }

            if(!empty($validated["idRequisition"])) {
                if (!$user->getHasBankAutorizationAttribute()) {
                    // Set idRequisition
                    $user->idRequisition = $validated["idRequisition"];
                } else {
                    return response(["message" => "Une banque a déjà été associé à cet utilisateur !"], 400);
                }
            }

            // Create and set account
            if(!empty($validated["account"])){
                if ($user->getHasBankAutorizationAttribute()) {
                    if (!$user->getHasAccountChoicesAttribute()) {
                        $user->addAccount($validated["account"]);
                    } else {
                        return response(["message" => "Un compte est déjà associé à cet utilisateur !"], 400);
                    }
                }else{
                    return response(["message" => "Merci de renseigner en priorité un id de réquisition !"], 400);
                }
            }

            $user->save();

            return response($user);
        }catch (\Exception $e){
            return response(
                [
                    "message" => "Oups une erreur est survenue !",
                    "exception" => [
                        "message" => $e->getMessage(),
                        "file" => $e->getFile(),
                        "trace" => $e->getTrace()
                    ]
                ]
            );
        }
    }
}
