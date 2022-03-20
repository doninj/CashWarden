<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API CashWarden",
 *      description="Toutes les requêtes API CashWarden",
 * )
 */
class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="API CashWarden Documentation",
     *      description="Cette documentation a pour objectif de présenter les différentes requêtes utilisées pour CashWarden",
     *      @OA\Contact(
     *          email="tim.gimenez26@gmail.com"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * )
     *
     * @OA\Tag(
     *     name="Projects",
     *     description="API Endpoints of Projects"
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
