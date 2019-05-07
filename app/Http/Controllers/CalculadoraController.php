<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculaCuotasRequest;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    private function fntFormato_SUA($pvalor, $pusar_function)
    {
        if ( !$pusar_function )
            return $pvalor;

            if ( isset($pvalor) && is_numeric($pvalor) ) {

                if ( is_float($pvalor) ) {

                    if ( substr( $pvalor, strpos($pvalor, ".") + 3, 1  ) <= 4 )
                        return floatval($pvalor);
                    else
                        return substr( $pvalor, 1, strpos($pvalor, ".") + 2 ) + 0.01;
                }
                else
                    return $pvalor;
            }
            else
                return 0;
    }

    //public function calcula(CalculaCuotasRequest $request)
    public function calcula()
    {
        //temp vars
        $numero_de_patrones = 2;
        $busar_function = true;
        $mes = 2;
        $sal_men = 5000;
        $num_decimales = 2;


        //Create empty arrays
        $tablas_calculos1 = [];
        $tablas_calculos2 = [];
        $tablas_calculos3 = [];
        $dias_mes = [
            '1' => 31,
            '2' => 28,
            '3' => 31,
            '4' => 30,
            '5' => 31,
            '6' => 30,
            '7' => 31,
            '8' => 31,
            '9' => 30,
            '10' => 31,
            '11' => 30,
            '12' => 31
        ];

        //Fill arrays with more elements, one for each month of a year
        for ($i = 0; $i <= ($numero_de_patrones - 1); $i++) {
            array_push($tablas_calculos3, [
                    'patron'             => null,
                    'dias_laborados'     => null,
                    'valor_proporcional' => null,
                    'total_cuota_obrera' => null
                ]
            );
        };

        $tablas_calculos3['0']['patron'] = "Luis Rubén Martínez";
        $tablas_calculos3['1']['patron'] = "FTL";
        $tablas_calculos3['0']['dias_laborados'] = 10;
        $tablas_calculos3['1']['dias_laborados'] = 12;

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
                'salario'           => [null, null, null],
                'v25vsmdf'          => [null, null, null],
                'v20vsmdf'          => [null, null, null],
                'dias'              => [null, null, null],
                'aus'               => [null, null, null],
                'inc'               => [null, null, null],
                'retiro'            => [null, null, null],
                'cv_pat'            => [null, null, null],
                'cv_ob'             => [null, null, null],
                'suma_rcv'          => [null, null, null],
                'aportacion'        => [null, null, null],
                'tot_pat'           => [null, null, null],
                'tot_ob'            => [null, null, null],
                'tot_rcv_infonavit' => [null, null, null]
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

        $title_calc01 = 'Calculadora';

        //BEGIN CALCULATIONS
        $tablas_calculos1[$mes]['salario'][0] = $this->fntFormato_SUA($sal_men, $busar_function) / $dias_mes[$mes];
        $tablas_calculos1[$mes]['salario'][0] = number_format( $tablas_calculos1[$mes]['salario'][0], $num_decimales );
        $tablas_calculos1[$mes]['salario'][1] = $tablas_calculos1[$mes]['salario'][0];

        $tablas_calculos1[$mes]['salario'][0] = $this->fntFormato_SUA($sal_men, $busar_function) / $dias_mes[$mes];

        return view('calc.calc_resultados', [
            'tablas_calculos1' => $tablas_calculos1,
            'tablas_calculos2' => $tablas_calculos2,
            'tablas_calculos3' => $tablas_calculos3,
            'title_calc01'     => $title_calc01,
        ]);
    }
}
