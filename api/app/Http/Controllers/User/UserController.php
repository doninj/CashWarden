<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Float_;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
       $userData = $user->load([
           'account.GetThreeTransactions',
           'account.transactionsForActualMounth',
           'account.transactions',
           'account.LatestBalances'
       ]);
       $collect = collect($userData->account->transactionsForActualMounth);
       $userData->account['totalSpendingOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
           if ($transaction->montant < 0)
               return $transaction->montant;
       })->sum()),2);
        $userData->account['totalIncomeOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
            if ($transaction->montant > 0)
                return $transaction->montant;
        })->sum()),2);
       $this->GetMonthAndYear($userData);
        return response()->json($user);
    }

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
    public function showUserWhenConnected(Request $request){
        $user = $request->user();
        $userData = $user->load([
            'account.GetThreeTransactions',
            'account.transactionsForActualMounth',
            'account.transactions'
        ]);
        $collect = collect($userData->account->transactionsForActualMounth);
        $userData->account['totalSpendingOfActualMonth'] =number_format(abs($collect->map(function ($transaction) {
            if ($transaction->montant < 0)
                return $transaction->montant;
        })->sum()),2);
        $this->GetMonth($userData);
        return response()->json($user);
    }
    /**
     * Show the form for creating a new resource.
     *
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        try {
            // Set nom prénom
            $user->firstName = empty($validated['firstName']) ? $user->firstName : $validated["firstName"];
            $user->lastName = empty($validated['lastName']) ? $user->firstName : $validated["lastName"];

            // Set password
            if (!empty($validated['password'])) {
                $user->setPassword($validated["password"]);
            }

            if (!empty($validated["bank_id"])) {
                $user->setBankById($validated["bank_id"]);
            }

            if(!empty($validated["idRequisition"])) {
                if (!$user->getHasBankAuthorizationAttribute()) {
                    // Set idRequisition
                    $user->idRequisition = $validated["idRequisition"];
                } else {
                    return response(["message" => "Une banque a déjà été associé à cet utilisateur !"], 400);
                }
            }

            // Create and set account
            if(!empty($validated["account"])) {
                if ($user->getHasBankAuthorizationAttribute()) {
                    if (!$user->getHasAccountChoicesAttribute()) {
                        $account = $validated["account"];
                        if (AccountController::accountExistInNordigenAPI($account["id"], $user->idRequisition)) {
                            $user->addAccount($account);
                        } else {
                            return response()->json([
                                "message" => "Le compte saisie n'appartient pas à l'id de réquisition associé à l'utilisateur !", 400
                            ]);
                        }
                    } else {
                        return response(["message" => "Un compte est déjà associé à cet utilisateur !"], 400);
                    }
                } else {
                    return response(["message" => "Merci de renseigner en priorité un id de réquisition !"], 400);
                }
            }

            $user->save();

            return response($user);
        } catch (\Exception $e) {
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
