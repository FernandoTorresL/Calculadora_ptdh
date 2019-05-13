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
                        return $this->fntTruncate($pvalor, 2);
                    else
                        return substr( $pvalor, 0, strpos($pvalor, ".") + 3 ) + 0.01;
                }
                else
                    return $pvalor;
            }
            else
                return 0;
    }

    private function esBisiesto($year=NULL) {
        return checkdate(2, 29, ($year==NULL)? date('Y'):$year);
    }

    //public function calcula(CalculaCuotasRequest $request)
    public function calcula()
    {
        //temp vars
        $numero_de_patrones = 4;
        $busar_function = true;
        $mes = 2;
        $sal_men = 15000;
        $num_decimales = 2;

        //Create empty arrays
        $t1 = [];
        $t2 = [];
        $t3 = [];

        //Fill array with number of days per month
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

        //if this year is "bisiesto", add one day to February
        if ($this->esBisiesto())
            $dias_mes['2'] = 29;

        for ($i = 0; $i <= 12; $i++) {
            array_push($t3, [
                'patron'               => [],
                'dias_laborados'       => [],
                'valor_proporcional'   => [],
                'total_cuota_obrera'   => [],
                'total_cuota_patronal' => []
                ]
            );

            array_push($t1, [
                'salario'   => [null, null, null],
                'v25vsmdf'  => [null, null, null],
                'art28_25'  => [null, null, null],
                'dias'      => [null, null, null],
                'aus'       => [null, null, null],
                'inc'       => [null, null, null],
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

        for ($i = 0; $i <= 12; $i++) {
            for ($j = 0; $j <= ($numero_de_patrones - 1); $j++) {
                array_push($t3[$i]['patron'], null);
                array_push($t3[$i]['dias_laborados'], null);
                array_push($t3[$i]['valor_proporcional'], null);
                array_push($t3[$i]['total_cuota_obrera'], null);
                array_push($t3[$i]['total_cuota_patronal'], null);
            }
        }

        $title_calc01 = 'Calculadora';

        //BEGIN CALCULATIONS

        //Table 01
        for ($mes = 1; $mes <= 12; $mes++) {

            //If mes is enero-2019, use another value for UMA
            if ($mes == 1 && $anio = 2019)
                $uma = env('UMA_ENERO_2019');
            else
                $uma = env('UMA_OTRO');

            $t1[$mes]['salario'][0] = $this->fntFormato_SUA($sal_men, $busar_function) / $dias_mes[$mes];
            $t1[$mes]['salario'][0] = number_format($t1[$mes]['salario'][0], $num_decimales);
            $t1[$mes]['salario'][1] = $t1[$mes]['salario'][0];
            $t1[$mes]['salario'][2] = null;

            $prod01 = env('I_V_PAT_VECES') * $uma;
            if ($t1[$mes]['salario'][0] > $prod01)
                $t1[$mes]['v25vsmdf'][0] = $prod01;
            else
                $t1[$mes]['v25vsmdf'][0] = $t1[$mes]['salario'][0];

            $prod02 = env('LIMITE_SBC_SUB') * $uma;
            if (is_null(env('LIMITE_SBC_SUB')))
                $t1[$mes]['v25vsmdf'][1] = null;
            else {
                if ($t1[$mes]['salario'][1] > $prod02)
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
            $t1[$mes]['cf'][0] = $this->fntFormato_SUA($t1[$mes]['cf'][0], $busar_function);
            $t1[$mes]['cf'][1] = $uma * $c1 * env('C_F') * env('SUBSIDIO');
            $t1[$mes]['cf'][1] = $this->fntFormato_SUA($t1[$mes]['cf'][1], $busar_function);
            $t1[$mes]['cf'][1] = $this->fntTruncate($t1[$mes]['cf'][1], 2);
            $t1[$mes]['cf'][2] = $t1[$mes]['cf'][0] - $t1[$mes]['cf'][1];

            $c3 = 3 * $uma;
            $prod03 = $t1[$mes]['v25vsmdf'][0] - $c3;
            if ($t1[$mes]['v25vsmdf'][0] > $c3) {
                $t1[$mes]['exc_pat'][0] = $prod03 * $c1 * env('EXC_PAT');
                $t1[$mes]['exc_ob'][0] = $prod03 * $c1 * env('EXC_OB');
            } else {
                $t1[$mes]['exc_pat'][0] = 0;
                $t1[$mes]['exc_ob'][0] = 0;
            }

            $t1[$mes]['exc_pat'][0] = $this->fntFormato_SUA($t1[$mes]['exc_pat'][0], $busar_function);
            $t1[$mes]['exc_ob'][0] = $this->fntFormato_SUA($t1[$mes]['exc_ob'][0], $busar_function);

            $t1[$mes]['exc_pat'][1] = $this->fntFormato_SUA($t1[$mes]['exc_pat'][1], $busar_function);
            $t1[$mes]['exc_pat'][1] = $t1[$mes]['exc_pat'][1] * env('SUBSIDIO');
            $t1[$mes]['exc_pat'][1] = $this->fntTruncate($t1[$mes]['exc_pat'][1], 2);

            $t1[$mes]['exc_ob'][1] = $this->fntFormato_SUA($t1[$mes]['exc_ob'][1], $busar_function);
            $t1[$mes]['exc_ob'][1] = $t1[$mes]['exc_ob'][1] * env('SUBSIDIO');
            $t1[$mes]['exc_ob'][1] = $this->fntTruncate($t1[$mes]['exc_ob'][1], 2);

            $t1[$mes]['exc_pat'][2] = $t1[$mes]['exc_pat'][0] - $t1[$mes]['exc_pat'][1];
            $t1[$mes]['exc_ob'][2] = $t1[$mes]['exc_ob'][0] - $t1[$mes]['exc_ob'][1];

            $c4 = $t1[$mes]['v25vsmdf'][0] * $c1;
            $c5 = $t1[$mes]['v25vsmdf'][1] * $c1;
            $t1[$mes]['pd_pat'][0] = $c4 * env('P_D_PAT');
            $t1[$mes]['pd_pat'][0] = $this->fntFormato_SUA($t1[$mes]['pd_pat'][0], $busar_function);
            $t1[$mes]['pd_pat'][1] = $c5 * env('P_D_PAT');
            $t1[$mes]['pd_pat'][1] = $this->fntFormato_SUA($t1[$mes]['pd_pat'][1], $busar_function);
            $t1[$mes]['pd_pat'][1] = $t1[$mes]['pd_pat'][1] * env('SUBSIDIO');
            $t1[$mes]['pd_pat'][1] = $this->fntTruncate($t1[$mes]['pd_pat'][1], 2);
            $t1[$mes]['pd_pat'][2] = $t1[$mes]['pd_pat'][0] - $t1[$mes]['pd_pat'][1];

            $t1[$mes]['pd_ob'][0] = $c4 * env('P_D_OB');
            $t1[$mes]['pd_ob'][0] = $this->fntFormato_SUA($t1[$mes]['pd_ob'][0], $busar_function);
            $t1[$mes]['pd_ob'][1] = $c5 * env('P_D_OB');
            $t1[$mes]['pd_ob'][1] = $this->fntFormato_SUA($t1[$mes]['pd_ob'][1], $busar_function);
            $t1[$mes]['pd_ob'][1] = $t1[$mes]['pd_ob'][1] * env('SUBSIDIO');
            $t1[$mes]['pd_ob'][1] = $this->fntTruncate($t1[$mes]['pd_ob'][1], 2);
            $t1[$mes]['pd_ob'][2] = $t1[$mes]['pd_ob'][0] - $t1[$mes]['pd_ob'][1];

            $t1[$mes]['gmp_pat'][0] = $c4 * env('G_M_P_PAT');
            $t1[$mes]['gmp_pat'][0] = $this->fntFormato_SUA($t1[$mes]['gmp_pat'][0], $busar_function);
            $t1[$mes]['gmp_pat'][1] = $c5 * env('G_M_P_PAT');
            $t1[$mes]['gmp_pat'][1] = $this->fntFormato_SUA($t1[$mes]['gmp_pat'][1], $busar_function);
            $t1[$mes]['gmp_pat'][1] = $t1[$mes]['gmp_pat'][1] * env('SUBSIDIO');
            $t1[$mes]['gmp_pat'][1] = $this->fntTruncate($t1[$mes]['gmp_pat'][1], 2);
            $t1[$mes]['gmp_pat'][2] = $t1[$mes]['gmp_pat'][0] - $t1[$mes]['gmp_pat'][1];

            $t1[$mes]['gmp_ob'][0] = $c4 * env('G_M_P_OB');
            $t1[$mes]['gmp_ob'][0] = $this->fntFormato_SUA($t1[$mes]['gmp_ob'][0], $busar_function);
            $t1[$mes]['gmp_ob'][1] = $c5 * env('G_M_P_OB');
            $t1[$mes]['gmp_ob'][1] = $this->fntFormato_SUA($t1[$mes]['gmp_ob'][1], $busar_function);
            $t1[$mes]['gmp_ob'][1] = $t1[$mes]['gmp_ob'][1] * env('SUBSIDIO');
            $t1[$mes]['gmp_ob'][1] = $this->fntTruncate($t1[$mes]['gmp_ob'][1], 2);
            $t1[$mes]['gmp_ob'][2] = $t1[$mes]['gmp_ob'][0] - $t1[$mes]['gmp_ob'][1];

            $c6 = $c1 - env('DIAS_AUS');
            $c7 = $t1[$mes]['v25vsmdf'][0] * $c6;
            $c8 = $t1[$mes]['v25vsmdf'][1] * $c6;
            $t1[$mes]['rt'][0] = $c7 * env('R_T');
            $t1[$mes]['rt'][0] = $this->fntFormato_SUA($t1[$mes]['rt'][0], $busar_function);
            $t1[$mes]['rt'][1] = $c8 * env('R_T');
            $t1[$mes]['rt'][1] = $this->fntFormato_SUA($t1[$mes]['rt'][1], $busar_function);
            $t1[$mes]['rt'][1] = $t1[$mes]['rt'][1] * env('SUBSIDIO');
            $t1[$mes]['rt'][1] = $this->fntTruncate($t1[$mes]['rt'][1], 2);
            $t1[$mes]['rt'][2] = $t1[$mes]['rt'][0] - $t1[$mes]['rt'][1];

            $c9 = $t1[$mes]['art28_25'][0] * $c6;
            $c10 = $t1[$mes]['art28_25'][1] * $c6;
            $t1[$mes]['iv_pat'][0] = $c9 * env('I_V_PAT');
            $t1[$mes]['iv_pat'][0] = $this->fntFormato_SUA($t1[$mes]['iv_pat'][0], $busar_function);
            $t1[$mes]['iv_pat'][1] = $c10 * env('I_V_PAT');
            $t1[$mes]['iv_pat'][1] = $this->fntFormato_SUA($t1[$mes]['iv_pat'][1], $busar_function);
            $t1[$mes]['iv_pat'][1] = $t1[$mes]['iv_pat'][1] * env('SUBSIDIO');
            $t1[$mes]['iv_pat'][1] = $this->fntTruncate($t1[$mes]['iv_pat'][1], 2);
            $t1[$mes]['iv_pat'][2] = $t1[$mes]['iv_pat'][0] - $t1[$mes]['iv_pat'][1];

            $c9 = $t1[$mes]['art28_25'][0] * $c6;
            $c10 = $t1[$mes]['art28_25'][1] * $c6;
            $t1[$mes]['iv_ob'][0] = $c9 * env('I_V_OB');
            $t1[$mes]['iv_ob'][0] = $this->fntFormato_SUA($t1[$mes]['iv_ob'][0], $busar_function);
            $t1[$mes]['iv_ob'][1] = $c10 * env('I_V_OB');
            $t1[$mes]['iv_ob'][1] = $this->fntFormato_SUA($t1[$mes]['iv_ob'][1], $busar_function);
            $t1[$mes]['iv_ob'][1] = $t1[$mes]['iv_ob'][1] * env('SUBSIDIO');
            $t1[$mes]['iv_ob'][1] = $this->fntTruncate($t1[$mes]['iv_ob'][1], 2);
            $t1[$mes]['iv_ob'][2] = $t1[$mes]['iv_ob'][0] - $t1[$mes]['iv_ob'][1];

            $c7 = $t1[$mes]['v25vsmdf'][0] * $c6;
            $c8 = $t1[$mes]['v25vsmdf'][1] * $c6;

            $t1[$mes]['gps'][0] = $c7 * env('G_P_S');
            $t1[$mes]['gps'][0] = $this->fntFormato_SUA($t1[$mes]['gps'][0], $busar_function);
            $t1[$mes]['gps'][1] = $c8 * env('G_P_S');
            $t1[$mes]['gps'][1] = $this->fntFormato_SUA($t1[$mes]['gps'][1], $busar_function);
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

            //Calcs to table 2
            for ($i = 0; $i <= 2; $i++) {
                $t2[$mes]['salario'][$i] = $t1[$mes]['salario'][$i];
                $t2[$mes]['v25vsmdf'][$i] = $t1[$mes]['v25vsmdf'][$i];
            }

            $c11 = env('LIMITE_SBC') * $uma;
            if ($t2[$mes]['salario'][0] > $c11)
                $t2[$mes]['v20vsmdf'][0] = $c11;
            else
                $t2[$mes]['v20vsmdf'][0] = $t2[$mes]['salario'][0];

            if (is_null(env('LIMITE_SBC_SUB')))
                $t2[$mes]['v20vsmdf'][1] = null;
            else {
                $c12 = env('LIMITE_SBC_SUB') * $uma;
                if ($t2[$mes]['salario'][1] > $c12)
                    $t2[$mes]['v20vsmdf'][1] = $c12;
                else
                    $t2[$mes]['v20vsmdf'][1] = $t2[$mes]['salario'][1];
            }

            $t2[$mes]['dias'][0] = $dias_mes[$mes];
            $t2[$mes]['dias'][1] = $dias_mes[$mes];
            $t2[$mes]['dias'][2] = null;

            $t2[$mes]['aus'][0] = env('DIAS_AUS');
            $t2[$mes]['aus'][1] = env('DIAS_AUS');
            $t2[$mes]['aus'][2] = null;

            $t2[$mes]['inc'][0] = env('DIAS_INC');
            $t2[$mes]['inc'][1] = env('DIAS_INC');
            $t2[$mes]['inc'][2] = null;

            $c12 = $dias_mes[$mes] - env('DIAS_AUS');
            $t2[$mes]['retiro'][0] = $t2[$mes]['v25vsmdf'][0] * $c12 * env('RETIRO');
            $t2[$mes]['retiro'][0] = $this->fntFormato_SUA($t2[$mes]['retiro'][0], $busar_function);
            $t2[$mes]['retiro'][1] = $t2[$mes]['v25vsmdf'][1] * $c12 * env('RETIRO');
            $t2[$mes]['retiro'][1] = $this->fntFormato_SUA($t2[$mes]['retiro'][1], $busar_function);
            $t2[$mes]['retiro'][1] = $t2[$mes]['retiro'][1] * env('SUBSIDIO');
            $t2[$mes]['retiro'][1] = $this->fntTruncate($t2[$mes]['retiro'][1], 2);
            $t2[$mes]['retiro'][2] = $t2[$mes]['retiro'][0] - $t2[$mes]['retiro'][1];

            $c13 = $c12 - env('DIAS_INC');
            $t2[$mes]['cv_pat'][0] = $t2[$mes]['v25vsmdf'][0] * $c13 * env('C_V_PAT');
            $t2[$mes]['cv_pat'][0] = $this->fntFormato_SUA($t2[$mes]['cv_pat'][0], $busar_function);
            $t2[$mes]['cv_pat'][1] = $t2[$mes]['v25vsmdf'][1] * $c13 * env('C_V_PAT');
            $t2[$mes]['cv_pat'][1] = $this->fntFormato_SUA($t2[$mes]['cv_pat'][1], $busar_function);
            $t2[$mes]['cv_pat'][1] = $t2[$mes]['cv_pat'][1] * env('SUBSIDIO');
            $t2[$mes]['cv_pat'][1] = $this->fntTruncate($t2[$mes]['cv_pat'][1], 2);
            $t2[$mes]['cv_pat'][2] = $t2[$mes]['cv_pat'][0] - $t2[$mes]['cv_pat'][1];

            $t2[$mes]['cv_ob'][0] = $t2[$mes]['v25vsmdf'][0] * $c13 * env('C_V_OB');
            $t2[$mes]['cv_ob'][0] = $this->fntFormato_SUA($t2[$mes]['cv_ob'][0], $busar_function);
            $t2[$mes]['cv_ob'][1] = $t2[$mes]['v25vsmdf'][1] * $c13 * env('C_V_PAT');
            $t2[$mes]['cv_ob'][1] = $this->fntFormato_SUA($t2[$mes]['cv_ob'][1], $busar_function);
            $t2[$mes]['cv_ob'][1] = $t2[$mes]['cv_ob'][1] * env('SUBSIDIO');
            $t2[$mes]['cv_ob'][1] = $this->fntTruncate($t2[$mes]['cv_ob'][1], 2);
            $t2[$mes]['cv_ob'][2] = $t2[$mes]['cv_ob'][0] - $t2[$mes]['cv_ob'][1];

            for ($i = 0; $i <= 1; $i++) {
                $t2[$mes]['suma_rcv'][$i] =
                    $t2[$mes]['cv_pat'][$i] +
                    $t2[$mes]['cv_ob'][$i] +
                    $t2[$mes]['retiro'][$i];
            }
            $t2[$mes]['suma_rcv'][2] = $t2[$mes]['suma_rcv'][0] - $t2[$mes]['suma_rcv'][1];

            $t2[$mes]['aportacion'][0] = $t2[$mes]['v25vsmdf'][0] * $c12 * env('AP_PAT_INFONAVIT');
            $t2[$mes]['aportacion'][0] = $this->fntFormato_SUA($t2[$mes]['aportacion'][0], $busar_function);
            $t2[$mes]['aportacion'][1] = $t2[$mes]['v25vsmdf'][1] * $c12 * env('AP_PAT_INFONAVIT');
            $t2[$mes]['aportacion'][1] = $this->fntFormato_SUA($t2[$mes]['aportacion'][1], $busar_function);
            $t2[$mes]['aportacion'][1] = $t2[$mes]['aportacion'][1] * env('SUBSIDIO');
            $t2[$mes]['aportacion'][1] = $this->fntTruncate($t2[$mes]['aportacion'][1], 2);
            $t2[$mes]['aportacion'][2] = $t2[$mes]['aportacion'][0] - $t2[$mes]['aportacion'][1];

            for ($i = 0; $i <= 2; $i++) {
                $t2[$mes]['tot_pat'][$i] = $t2[$mes]['retiro'][$i] + $t2[$mes]['cv_pat'][$i] + $t2[$mes]['aportacion'][$i];
                $t2[$mes]['tot_ob'][$i] = $t2[$mes]['cv_ob'][$i];
                $t2[$mes]['tot_rcv_infonavit'][$i] = $t2[$mes]['tot_pat'][$i] + $t2[$mes]['tot_ob'][$i];
            }

            //Temporary vars
            $t3[$mes]['patron'][0] = "A";
            $t3[$mes]['patron'][1] = "B";
            $t3[$mes]['patron'][2] = "C";
            $t3[$mes]['patron'][3] = "D";

            $t3[$mes]['dias_laborados'][0] = 10;
            $t3[$mes]['dias_laborados'][1] = 11;
            $t3[$mes]['dias_laborados'][2] = 12;
            $t3[$mes]['dias_laborados'][3] = 13;
        }

        //Calcs for table #3
        for ($mes = 1; $mes <= 12; $mes++) {
            $total_dias = 0;
            for ($i = 0; $i <= ($numero_de_patrones - 1); $i++) {
                $total_dias = $total_dias + $t3[$mes]['dias_laborados'][$i];
            }

            for ($i = 0; $i <= ($numero_de_patrones - 1); $i++) {

                if ($total_dias == 0)
                    $t3[$mes]['valor_proporcional'][$i] = 0;
                else
                    $t3[$mes]['valor_proporcional'][$i] = $t3[$mes]['dias_laborados'][$i] / $total_dias;

                if (env('SMDF') == $t1[$mes]['salario'][0]) {
                    //Calc 'Cuota Obrera' when salario = SMDF
                    $t3[$mes]['total_cuota_obrera'][$i] = 0;

                    //Calc 'Cuota Patronal' when salario = SMDF
                    $t3[$mes]['total_cuota_patronal'][$i] =
                        $this->fntFormato_SUA(
                            $t3[$mes]['valor_proporcional'][$i] *
                            ( $t1[$mes]['cuota_ob'][2] + $t2[$mes]['tot_ob'][2] )
                        , $busar_function ) +
                        $this->fntFormato_SUA(
                            $t3[$mes]['valor_proporcional'][$i] *
                            ( $t1[$mes]['cuota_pat'][2] + $t2[$mes]['tot_pat'][2] )
                        , $busar_function );
                }
                else {
                    //Calc 'Cuota Obrera'
                    $t3[$mes]['total_cuota_obrera'][$i] =
                        $t3[$mes]['valor_proporcional'][$i] *
                        ( $t1[$mes]['cuota_ob'][2] + $t2[$mes]['tot_ob'][2] );
                    $t3[$mes]['total_cuota_obrera'][$i] =
                        $this->fntFormato_SUA( $t3[$mes]['total_cuota_obrera'][$i], $busar_function );

                    //Calc 'Cuota Patronal'
                    $t3[$mes]['total_cuota_patronal'][$i] =
                        $t3[$mes]['valor_proporcional'][$i] *
                        ( $t1[$mes]['cuota_pat'][2] + $t2[$mes]['tot_pat'][2] );
                    $t3[$mes]['total_cuota_patronal'][$i] =
                        $this->fntFormato_SUA( $t3[$mes]['total_cuota_patronal'][$i], $busar_function );
                }
            }
        }

        return view('calc.calc_resultados', [
            'numero_de_patrones' => $numero_de_patrones,
            't1' => $t1,
            't2' => $t2,
            't3' => $t3,
            'title_calc01'     => $title_calc01,
        ]);
    }
}
