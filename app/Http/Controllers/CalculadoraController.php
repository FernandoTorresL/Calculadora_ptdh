<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculaCuotasRequest;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{

    public function calcula(CalculaCuotasRequest $request)
    {
        //dd($request->all());
        //$this->validate($request, [

        //]);

        //return "Llegó";

        $title_calc01 = 'Calculadora02';
        return view('calc.calc_resultados', [
            'title_calc01' => $title_calc01,
        ]);
        //return 'Calculando...';
    }
}
