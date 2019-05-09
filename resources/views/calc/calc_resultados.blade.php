@extends('layouts.app')

@section('title')
    Calculadora PTdH
@endsection

@section('content')
    <div class="jumbotron text-center">
        <h1>Resultados!</h1>
        {{ $title_calc01 }}
    </div>

    <div>
        <h3>Tabla 01 - Cálculos previos</h3>
        <table class="table table-sm table-striped table-hover">
            <tbody>
            @foreach ($t1 as $clave_mes => $mes)
                @if ($loop->first)
                    @continue
                @endif
                <tr class="thead-dark">
                    <th scope="col">Mes</th>
                    <th scope="col">Concepto</th>
                    <th scope="col">Row 0</th>
                    <th scope="col">Row 1</th>
                    <th scope="col">Row 2 - Total</th>
                </tr>
                @foreach ($mes as $clave_concepto => $valor_concepto)
                    <tr>
                        <th scope="row">{{ $clave_mes }}</th>
                        <td>{{ $clave_concepto }}</td>
                    @foreach ($valor_concepto as $key => $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

    <br>
    <br>

    <div>
        <h3>Tabla 02 - Cálculos previos</h3>
        <table class="table table-sm table-striped table-hover">
            <tbody>
            @foreach ($t2 as $clave_mes => $mes)
                @if ($loop->first)
                    @continue
                @endif
                <tr class="thead-dark">
                    <th scope="col">Mes</th>
                    <th scope="col">Concepto</th>
                    <th scope="col">Row 0</th>
                    <th scope="col">Row 1</th>
                    <th scope="col">Row 2 - Total</th>
                </tr>
                @foreach ($mes as $clave_concepto => $valor)
                    <tr>
                        <th scope="row">{{ $clave_mes }}</th>
                        <td>{{ $clave_concepto }}</td>
                    @foreach ($valor as $key => $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

    <br>
    <br>

    <div>
        <h3>Tabla 3</h3>
        <table class="table table-sm table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Mes</th>
                    <th scope="col">#</th>
                    <th scope="col">Nombre Patrón</th>
                    <th scope="col">Días laborados</th>
                    <th scope="col">Valor Proporcional</th>
                    <th scope="col">Total Cuota Obrera</th>
                    <th scope="col">Total Cuota Patronal</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($t3 as $clave_mes => $mes)
                @if ($loop->first)
                    @continue
                @endif
                @for ($i = 1; $i <= $numero_de_patrones; $i++)
                    <tr>
                        @if ($i == 1)
                            <th scope="col">{{ $clave_mes }}</th>
                        @else
                            <th></th>
                        @endif
                            <td>{{ $i }}</td>
                        @foreach ($mes as $clave_registro => $elemento_registro)
                            <td>{{ $elemento_registro[$i-1] }}</td>
                        @endforeach
                    </tr>
                @endfor
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
