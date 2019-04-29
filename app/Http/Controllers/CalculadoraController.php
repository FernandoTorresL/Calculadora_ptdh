<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculadoraController extends Controller
{

    public function calcula (Request $request)
    {
        //dd($request->all());
        $title_calc01 = 'Calculadora02';
        return view('calc.calc_resultados', [
            'title_calc01' => $title_calc01,
        ]);
        //return 'Calculando...';
    }
}
