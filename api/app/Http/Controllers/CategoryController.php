<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category, Request $request)
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
        $account = $request->user()->account;
        $categoryData = $category->with(['transactions' => function ($query) use ($account, $year, $month) {
        $query->where('account_id', '=', $account->id)->whereMonth('dateTransaction', '=', $month)->WhereYear('dateTransaction','=',$year)->get();
        }
        ])->get();
        foreach ($categoryData as $value) {
        $totalIncome = 0;
        $totalSpendng = 0;
            foreach ($value['transactions'] as $transaction) {
                if($transaction->montant > 0) {
                    $totalIncome = $totalIncome + $transaction->montant;
                }
                else {
                    $totalSpendng = $totalSpendng + $transaction->montant;
                }
            }
            $value['totalSpending'] = abs($totalSpendng);
            $value['totalIncome'] = abs($totalIncome) ;
        }
        return response()->json($categoryData);
    }
}
