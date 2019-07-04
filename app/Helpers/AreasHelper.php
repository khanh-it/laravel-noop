<?php

namespace App\Helpers;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
/**
 * @author  13.09.2017
 */
class AreasHelper
{

    // public function __construct(Request $request){
    //     $this->request = $request;
    // }
    public function checkAreas()
    {

        $exchangeName = session('lastExchange') ?: "rivers";

        return session('areas.' . $exchangeName);
    }
}