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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
