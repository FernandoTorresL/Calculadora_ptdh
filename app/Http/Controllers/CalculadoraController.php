<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculaCuotasRequest;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    /**
     * Truncate a float number, example: <code>truncate(-1.49999, 2); // returns -1.49
     * truncate(.49999, 3); // returns 0.499
     * </code>
     * @param float $val Float number to be truncate
     * @param int f Number of precision
     * @return float
     */
    function fntTruncate($val, $f="0")
    {
        if(($p = strpos($val, '.')) !== false) {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }
        return $val;
    }

    private function fntFormato_SUA($pvalor, $pusar_function)
    {
        if ( !$pusar_function )
            return $pvalor;

            if ( isset($pvalor) && is_numeric($pvalor) ) {

                if ( is_float($pvalor) ) {
                    if ( substr( $pvalor, strpos($pvalor, ".") + 3, 1  ) <= 4 )
                        return floatval($pvalor);
                    else
                        return substr( $pvalor, 0, strpos($pvalor, ".") + 3 ) + 0.01;
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
        $t1 = [];
        $t2 = [];
        $t3 = [];
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
            array_push($t3, [
                    'patron'             => null,
                    'dias_laborados'     => null,
                    'valor_proporcional' => null,
                    'total_cuota_obrera' => null
                ]
            );
        };

        $t3['0']['patron'] = "Luis Rubén Martínez";
        $t3['1']['patron'] = "FTL";
        $t3['0']['dias_laborados'] = 10;
        $t3['1']['dias_laborados'] = 12;

        for ($i = 0; $i <= 12; $i++) {
            array_push($t1, [
                'salario'   => [null, null, null],
                'v25vsmdf'  => [null, null, null],
                'art28_25' => [null, null, null],
                'dias'     => [null, null, null],
                'aus'      => [null, null, null],
                'inc'      => [null, null, null],
                'cf'       => [null, null, null],
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

            array_push($t2, [
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

        //dd($t2);
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

        //If mes is enero-2019, use another value for UMA
        if ( $mes == 1 && $anio = 2019 )
            $uma = env('UMA_ENERO_2019');
        else
            $uma = env('UMA_OTRO');

        $t1[$mes]['salario'][0] = $this->fntFormato_SUA($sal_men, $busar_function) / $dias_mes[$mes];
        $t1[$mes]['salario'][0] = number_format( $t1[$mes]['salario'][0], $num_decimales );
        $t1[$mes]['salario'][1] = $t1[$mes]['salario'][0];
        $t1[$mes]['salario'][2] = null;

        $prod01 = env('I_V_PAT_VECES') * $uma;
        if ( $t1[$mes]['salario'][0] > $prod01 )
            $t1[$mes]['v25vsmdf'][0] = $prod01;
        else
            $t1[$mes]['v25vsmdf'][0] = $t1[$mes]['salario'][0];

        $prod02 = env('LIMITE_SBC_SUB') * $uma;
        if ( is_null( env('LIMITE_SBC_SUB') ) )
            $t1[$mes]['v25vsmdf'][1] = null;
        else {
            if ( $t1[$mes]['salario'][1] > $prod02 )
                $t1[$mes]['v25vsmdf'][1] = $prod02;
            else
                $t1[$mes]['v25vsmdf'][1] = $t1[$mes]['salario'][1];
        }
        $t1[$mes]['v25vsmdf'][2] = null;

        $t1[$mes]['art28_25'][0] = $t1[$mes]['v25vsmdf'][0];
        $t1[$mes]['art28_25'][1] = $t1[$mes]['v25vsmdf'][1];
        $t1[$mes]['art28_25'][2] = null;

        $t1[$mes]['dias'][0] = $dias_mes[$mes];
        $t1[$mes]['dias'][1] = $dias_mes[$mes];
        $t1[$mes]['dias'][2] = $dias_mes[$mes];

        $t1[$mes]['aus'][0] = env('DIAS_AUS');
        $t1[$mes]['aus'][1] = env('DIAS_AUS');
        $t1[$mes]['aus'][2] = null;

        $t1[$mes]['inc'][0] = env('DIAS_INC');
        $t1[$mes]['inc'][1] = env('DIAS_INC');
        $t1[$mes]['inc'][2] = null;

        $c1 = $dias_mes[$mes] - env('DIAS_INC');
        $t1[$mes]['cf'][0] = $uma * $c1 * env('C_F');
        $t1[$mes]['cf'][0] = $this->fntFormato_SUA( $t1[$mes]['cf'][0], $busar_function );
        $t1[$mes]['cf'][1] = $uma * $c1 * env('C_F') * env('SUBSIDIO');
        $t1[$mes]['cf'][1] = $this->fntFormato_SUA($t1[$mes]['cf'][1], $busar_function );
        $t1[$mes]['cf'][1] = $this->fntTruncate( $t1[$mes]['cf'][1], 2 );
        $t1[$mes]['cf'][2] = $t1[$mes]['cf'][0] - $t1[$mes]['cf'][1];

        $c3 = 3 * $uma;
        $prod03 = $t1[$mes]['v25vsmdf'][0] - $c3;
        if ($t1[$mes]['v25vsmdf'][0] > $c3) {
            $t1[$mes]['exc_pat'][0] = $prod03 * $c1 * env('EXC_PAT');
            $t1[$mes]['exc_ob'][0]  = $prod03 * $c1 * env('EXC_OB');
        }
        else {
            $t1[$mes]['exc_pat'][0] = 0;
            $t1[$mes]['exc_ob'][0]  = 0;
        }

        $t1[$mes]['exc_pat'][0] = $this->fntFormato_SUA( $t1[$mes]['exc_pat'][0], $busar_function );
        $t1[$mes]['exc_ob'][0]  = $this->fntFormato_SUA( $t1[$mes]['exc_ob'][0], $busar_function );

        $t1[$mes]['exc_pat'][1] = $this->fntFormato_SUA($t1[$mes]['exc_pat'][1], $busar_function);
        $t1[$mes]['exc_pat'][1] = $t1[$mes]['exc_pat'][1] * env('SUBSIDIO');
        $t1[$mes]['exc_pat'][1] = $this->fntTruncate($t1[$mes]['exc_pat'][1], 2);

        $t1[$mes]['exc_ob'][1] = $this->fntFormato_SUA($t1[$mes]['exc_ob'][1], $busar_function);
        $t1[$mes]['exc_ob'][1] = $t1[$mes]['exc_ob'][1] * env('SUBSIDIO');
        $t1[$mes]['exc_ob'][1] = $this->fntTruncate($t1[$mes]['exc_ob'][1], 2);

        $t1[$mes]['exc_pat'][2] = $t1[$mes]['exc_pat'][0] - $t1[$mes]['exc_pat'][1];
        $t1[$mes]['exc_ob'][2]  = $t1[$mes]['exc_ob'][0] - $t1[$mes]['exc_ob'][1];

        $c4 = $t1[$mes]['v25vsmdf'][0] * $c1;
        $c5 = $t1[$mes]['v25vsmdf'][1] * $c1;
        $t1[$mes]['pd_pat'][0] = $c4 *  env('P_D_PAT');
        $t1[$mes]['pd_pat'][0] = $this->fntFormato_SUA( $t1[$mes]['pd_pat'][0], $busar_function);
        $t1[$mes]['pd_pat'][1] = $c5 *  env('P_D_PAT');
        $t1[$mes]['pd_pat'][1] = $this->fntFormato_SUA( $t1[$mes]['pd_pat'][1], $busar_function);
        $t1[$mes]['pd_pat'][1] = $t1[$mes]['pd_pat'][1] * env('SUBSIDIO');
        $t1[$mes]['pd_pat'][1] = $this->fntTruncate($t1[$mes]['pd_pat'][1], 2);
        $t1[$mes]['pd_pat'][2]  = $t1[$mes]['pd_pat'][0] - $t1[$mes]['pd_pat'][1];

        $t1[$mes]['pd_ob'][0] = $c4 * env('P_D_OB');
        $t1[$mes]['pd_ob'][0] = $this->fntFormato_SUA( $t1[$mes]['pd_ob'][0], $busar_function);
        $t1[$mes]['pd_ob'][1] = $c5 * env('P_D_OB');
        $t1[$mes]['pd_ob'][1] = $this->fntFormato_SUA( $t1[$mes]['pd_ob'][1], $busar_function);
        $t1[$mes]['pd_ob'][1] = $t1[$mes]['pd_ob'][1] * env('SUBSIDIO');
        $t1[$mes]['pd_ob'][1] = $this->fntTruncate($t1[$mes]['pd_ob'][1], 2);
        $t1[$mes]['pd_ob'][2]  = $t1[$mes]['pd_ob'][0] - $t1[$mes]['pd_ob'][1];

        $t1[$mes]['gmp_pat'][0] = $c4 * env('G_M_P_PAT');
        $t1[$mes]['gmp_pat'][0] = $this->fntFormato_SUA( $t1[$mes]['gmp_pat'][0], $busar_function);
        $t1[$mes]['gmp_pat'][1] = $c5 * env('G_M_P_PAT');
        $t1[$mes]['gmp_pat'][1] = $this->fntFormato_SUA( $t1[$mes]['gmp_pat'][1], $busar_function);
        $t1[$mes]['gmp_pat'][1] = $t1[$mes]['gmp_pat'][1] * env('SUBSIDIO');
        $t1[$mes]['gmp_pat'][1] = $this->fntTruncate($t1[$mes]['gmp_pat'][1], 2);
        $t1[$mes]['gmp_pat'][2]  = $t1[$mes]['gmp_pat'][0] - $t1[$mes]['gmp_pat'][1];

        $t1[$mes]['gmp_ob'][0] = $c4 * env('G_M_P_OB');
        $t1[$mes]['gmp_ob'][0] = $this->fntFormato_SUA( $t1[$mes]['gmp_ob'][0], $busar_function);
        $t1[$mes]['gmp_ob'][1] = $c5 * env('G_M_P_OB');
        $t1[$mes]['gmp_ob'][1] = $this->fntFormato_SUA( $t1[$mes]['gmp_ob'][1], $busar_function);
        $t1[$mes]['gmp_ob'][1] = $t1[$mes]['gmp_ob'][1] * env('SUBSIDIO');
        $t1[$mes]['gmp_ob'][1] = $this->fntTruncate($t1[$mes]['gmp_ob'][1], 2);
        $t1[$mes]['gmp_ob'][2]  = $t1[$mes]['gmp_ob'][0] - $t1[$mes]['gmp_ob'][1];

        $c6 = $c1 - env('DIAS_AUS');
        $c7 = $t1[$mes]['v25vsmdf'][0] * $c6;
        $c8 = $t1[$mes]['v25vsmdf'][1] * $c6;
        $t1[$mes]['rt'][0] = $c7 * env('R_T');
        $t1[$mes]['rt'][0] = $this->fntFormato_SUA( $t1[$mes]['rt'][0], $busar_function);
        $t1[$mes]['rt'][1] = $c8 * env('R_T');
        $t1[$mes]['rt'][1] = $this->fntFormato_SUA( $t1[$mes]['rt'][1], $busar_function);
        $t1[$mes]['rt'][1] = $t1[$mes]['rt'][1] * env('SUBSIDIO');
        $t1[$mes]['rt'][1] = $this->fntTruncate($t1[$mes]['rt'][1], 2);
        $t1[$mes]['rt'][2]  = $t1[$mes]['rt'][0] - $t1[$mes]['rt'][1];

        $c9 = $t1[$mes]['art28_25'][0] * $c6;
        $c10 = $t1[$mes]['art28_25'][1] * $c6;
        $t1[$mes]['iv_pat'][0] = $c9 * env('I_V_PAT');
        $t1[$mes]['iv_pat'][0] = $this->fntFormato_SUA( $t1[$mes]['iv_pat'][0], $busar_function);
        $t1[$mes]['iv_pat'][1] = $c10 * env('I_V_PAT');
        $t1[$mes]['iv_pat'][1] = $this->fntFormato_SUA( $t1[$mes]['iv_pat'][1], $busar_function);
        $t1[$mes]['iv_pat'][1] = $t1[$mes]['iv_pat'][1] * env('SUBSIDIO');
        $t1[$mes]['iv_pat'][1] = $this->fntTruncate($t1[$mes]['iv_pat'][1], 2);
        $t1[$mes]['iv_pat'][2] = $t1[$mes]['iv_pat'][0] - $t1[$mes]['iv_pat'][1];

        $c9 = $t1[$mes]['art28_25'][0] * $c6;
        $c10 = $t1[$mes]['art28_25'][1] * $c6;
        $t1[$mes]['iv_ob'][0] = $c9 * env('I_V_OB');
        $t1[$mes]['iv_ob'][0] = $this->fntFormato_SUA( $t1[$mes]['iv_ob'][0], $busar_function);
        $t1[$mes]['iv_ob'][1] = $c10 * env('I_V_OB');
        $t1[$mes]['iv_ob'][1] = $this->fntFormato_SUA( $t1[$mes]['iv_ob'][1], $busar_function);
        $t1[$mes]['iv_ob'][1] = $t1[$mes]['iv_ob'][1] * env('SUBSIDIO');
        $t1[$mes]['iv_ob'][1] = $this->fntTruncate($t1[$mes]['iv_ob'][1], 2);
        $t1[$mes]['iv_ob'][2] = $t1[$mes]['iv_ob'][0] - $t1[$mes]['iv_ob'][1];

        $c7 = $t1[$mes]['v25vsmdf'][0] * $c6;
        $c8 = $t1[$mes]['v25vsmdf'][1] * $c6;

        $t1[$mes]['gps'][0] = $c7 * env('G_P_S');
        $t1[$mes]['gps'][0] = $this->fntFormato_SUA( $t1[$mes]['gps'][0], $busar_function);
        $t1[$mes]['gps'][1] = $c8 * env('G_P_S');
        $t1[$mes]['gps'][1] = $this->fntFormato_SUA( $t1[$mes]['gps'][1], $busar_function);
        $t1[$mes]['gps'][1] = $t1[$mes]['gps'][1] * env('SUBSIDIO');
        $t1[$mes]['gps'][1] = $this->fntTruncate($t1[$mes]['gps'][1], 2);
        $t1[$mes]['gps'][2] = $t1[$mes]['gps'][0] - $t1[$mes]['gps'][1];

        for ($i = 0; $i <= 2; $i++) {
            $t1[$mes]['cuota_pat'][$i] =
                $t1[$mes]['cf'][$i] +
                $t1[$mes]['exc_pat'][$i] +
                $t1[$mes]['pd_pat'][$i] +
                $t1[$mes]['gmp_pat'][$i] +
                $t1[$mes]['rt'][$i] +
                $t1[$mes]['iv_pat'][$i] +
                $t1[$mes]['gps'][$i];

            $t1[$mes]['cuota_ob'][$i] =
                $t1[$mes]['exc_ob'][$i] +
                $t1[$mes]['pd_ob'][$i] +
                $t1[$mes]['gmp_ob'][$i] +
                $t1[$mes]['iv_ob'][$i];

            $t1[$mes]['total_cop'][$i] =
                $t1[$mes]['cuota_pat'][$i] +
                $t1[$mes]['cuota_ob'][$i];
        }

        return view('calc.calc_resultados', [
            't1' => $t1,
            't2' => $t2,
            't3' => $t3,
            'title_calc01'     => $title_calc01,
        ]);
    }
}
