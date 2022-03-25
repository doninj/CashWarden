<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     *
     *
     * @OA\Get(
     *      path="/api/transactions",
     *      operationId="getTransactions",
     *      tags={"User"},
     *      summary="Permet de récupérer une liste de transactions",
     *      description="Obtiens les transactions de l'utilisateur paginé",
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
     *         description="Lorsqu'un utilisateur est connecté et qu'il possède déjà un compte",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="current_page",
     *                 type="number",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example="153"),
     *                     @OA\Property(property="label", type="string", example="VIREMENT EN VOTRE FAVEUR CONDUENT BUSINESS SOLUTIONS (FRA"),
     *                     @OA\Property(property="montant", type="number", example="1250"),
     *                     @OA\Property(property="monnaie", type="string", example="EUR"),
     *                     @OA\Property(property="dateTransaction", type="date", example="2022-03-28"),
     *                     @OA\Property(property="status", type="string", example="booked"),
     *                     @OA\Property(property="account_id", type="string", example="e806f95a-6455-46a0-f357-d4d15fe4fd90"),
     *                     @OA\Property(property="category_id", type="integer", example="1"),
     *                     @OA\Property(property="created_at", type="date", example="2022-03-14T23:29:03.000000Z"),
     *                     @OA\Property(property="updated_at", type="date", example="2022-03-14T23:29:03.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $account = $user->account;
        $transactions = $account->transactions()->orderByDesc("dateTransaction");

        return response()->json($transactions->paginate(6));
    }
    public function transactionsPerMonth(Request $request)
    {
        $monthInFr = $request->date;
        $year = $request->annee;
        if($year != null && $monthInFr != null) {
        $monthInEng= Carbon::translateTimeString($monthInFr);
        $month = Carbon::parse($monthInEng)->month;
        } else {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
        }
        $user = $request->user();
        $account = $user->account;
        $transaction['allTransactions'] = $account->transactions()->whereMonth('dateTransaction', '=', $month)->WhereYear('dateTransaction','=',$year)->orderBy('dateTransaction','desc')->get();
        $transaction['totalDepenses'] = number_format(abs($transaction['allTransactions']->map(function ($transaction) {
            if ($transaction->montant < 0)
                return $transaction->montant;
        })->sum()),2);
        $transaction['totalIncomes'] = number_format(abs($transaction['allTransactions']->map(function ($transaction) {
            if ($transaction->montant > 0)
                return $transaction->montant;
        })->sum()),2);
        return response()->json($transaction);
    }
}
