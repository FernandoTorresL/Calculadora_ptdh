<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculaCuotasRequest;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{

    //public function calcula(CalculaCuotasRequest $request)
    public function calcula()
    {
        $tmptabla1 =
            array(
                '1' =>
                    array(
                       'salario' =>
                            array('178.57', '200', '300'),
                       'v25vsmdf' =>
                            array('11', '22', '33')
            ), '2' =>
                    array(
                    'salario' =>
                        array('a', 'b', 'c'),
                    'v25vsmdf' =>
                        array('111', '222', '333')
            )
        );

        //dd($tmptabla1['2']);
        //dd($tmptabla1);
        //dd($tmptabla1['2']['v25vsmdf'][1]);
        //=222

        //$this->validate($request, [

        //]);

        //return "Llegó";

        $title_calc01 = 'Calculadora02';
        return view('calc.calc_resultados', [
            'tmptabla1' => $tmptabla1,
            'title_calc01' => $title_calc01,
        ]);
    }
}
