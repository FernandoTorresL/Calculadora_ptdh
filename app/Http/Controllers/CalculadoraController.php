<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculaCuotasRequest;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{

    //public function calcula(CalculaCuotasRequest $request)
    public function calcula()
    {

        //Create empty arrays
        $tablas_calculos1 = [];
        $tablas_calculos2 = [];

        //Fill arrays with more elements, one for each month of a year
        for ($i = 0; $i <= 12; $i++) {
            array_push($tablas_calculos1, [
                'salario'   => [null, null, null],
                'v25vsmdf'  => [null, null, null],
                'art28_25'  => [null, null, null],
                'cf'        => [null, null, null],
                'exc_pat'   => [null, null, null],
                'exc_ob'    => [null, null, null],
                'pd_pat'    => [null, null, null],
                'pd_ob'     => [null, null, null],
                'gmp_pat'   => [null, null, null],
                'gmp_ob'    => [null, null, null],
                'rt'        => [null, null, null],
                'iv_pat'    => [null, null, null],
                'iv_ob'     => [null, null, null],
                'gps'       => [null, null, null],
                'cuota_pat' => [null, null, null],
                'cuota_ob'  => [null, null, null],
                'total_cop' => [null, null, null]
            ]);

            array_push($tablas_calculos2, [
                'salario'   => [null, null, null],
                'v25vsmdf'  => [null, null, null],
                'v20vsmdf'  => [null, null, null],
                'dias'  => [null, null, null],
                'aus'  => [null, null, null],
                'inc'  => [null, null, null],
                'retiro'  => [null, null, null],
                'cv_pat'        => [null, null, null],
                'cv_ob'   => [null, null, null],
                'suma_rcv'    => [null, null, null],
                'aportacion'    => [null, null, null],
                'tot_pat'     => [null, null, null],
                'tot_ob'   => [null, null, null],
                'tot_rcv_infonavit'    => [null, null, null]
            ]);
        }



        //dd($tablas_calculos2);
        //$tmptabla1['1']['exc_ob'][1] = 1978;
        //$tmptabla1['2']['pd_pat'][2] = 2019;
        //$tmptabla1['12']['total_cop'][1] = 205000;
        //dd($tmptabla1['2']);
        //dd($tmptabla1);
        //dd($tmptabla1['2']['v25vsmdf'][1]);
        //=222

        //$this->validate($request, [

        //]);

        $title_calc01 = 'Calculadora02';
        return view('calc.calc_resultados', [
            'tablas_calculos1' => $tablas_calculos1,
            'tablas_calculos2' => $tablas_calculos2,
            'title_calc01' => $title_calc01,
        ]);
    }
}
