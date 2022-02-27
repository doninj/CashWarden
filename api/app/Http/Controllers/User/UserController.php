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
        $data = $request->validated();

        try{
            // Set nom prénom
            $user->firstName = empty($validated['firstName']) ? $user->firstName : $data["firstName"];
            $user->lastName = empty($validated['lastName']) ? $user->firstName : $data["lastName"];

            // Set password
            if(!empty($validated['password'])){
                $user->setPassword($data["password"]);
            }

            if(!$user->getHasBankAutorizationAttribute()){
                // Set idRequisition
                $user->idRequisition = empty($validated["idRequisition"]) ? $user->idRequisition : $data["idRequisition"];
            }else{
                return response(["message" => "Une banque a déjà été associé à cet utilisateur !"], 400);
            }

            // Create and set account
            if(!empty($validated["account"])){
                if(!$user->getHasAccountChoicesAttribute()){
                    $user->addAccount($validated["account"]);
                }else{
                    return response(["message" => "Un compte est déjà associé à cet utilisateur !"], 400);
                }
            }

            $user->save();

            return response($user);
        }catch (\Exception $e){
            return response(
                [
                    "message" => "Oups une erreur est survenue !",
                    "exception" => [
                        $e->getTrace()
                    ]
                ]
            );
        }
    }
}
