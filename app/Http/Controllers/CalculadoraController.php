<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    public function home ()
    {
        $title_calc01 = 'Calculadora';
        return view('calc.calc_home', [
            'title_calc01' => $title_calc01,
        ]);
    }
}
