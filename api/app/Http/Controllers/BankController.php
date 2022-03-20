<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     *
     *
     * @OA\Get(
     *      path="/api/banks",
     *      operationId="getBanks",
     *      tags={"NordigenAccounts"},
     *      summary="Permet d'obtenir la liste des banques",
     *      description="Retourne la liste des banques",
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
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                     @OA\Property(
     *                         property="id", type="string", example="ABS_CMBRFR2BXXX"
     *                     ),
     *                     @OA\Property(
     *                         property="name", type="string", example="Arkéa Banking Services"
     *                     ),
     *                     @OA\Property(
     *                         property="logo", type="string", example="https://cdn.nordigen.com/ais/ABS_CMBRFR2BXXX.png"
     *                     )
     *             )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $banks = Bank::all();
        return response()->json($banks);
    }
}
