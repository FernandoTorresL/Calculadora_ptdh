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

        <table class="table">
            <thead class="thead">
            <tr>
                <th scope="col">Tabla 3</th>
            </tr>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre Patrón</th>
                <th scope="col">Días laborados</th>
                <th scope="col">Valor Proporcional</th>
                <th scope="col">Total Cuota Obrera</th>
                <th scope="col">Total Cuota Patronal</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($t3 as $patron)
                <tr>
                    <th scope="col">{{ key($t3)+1 }}</th>
                    <td>{{ $patron['patron'] }}</td>
                    <td>{{ $patron['dias_laborados'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @php next($t3); @endphp
            @endforeach
            </tbody>
        </table>

    </div>

    <div>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tabla 1</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($t1 as $mes)
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Mes: {{ key($t1) }}</th>
                </tr>
                <tr>
                    <th scope="col">Concepto</th>
                    <th scope="col">Row 0</th>
                    <th scope="col">Row 1</th>
                    <th scope="col">Row 2 - Total</th>
                </tr>
                </thead>
                @foreach ($mes as $concepto)
                        <tr>
                            <th scope="row">Tabla 1 | Mes: {{ key($t1) . ' | ' . key($mes) }}</th>
                    @foreach ($concepto as $key => $value)
                            <td>{{ $value }}</td>
                    @endforeach
                        </tr>
                    @php next($mes); @endphp

                @endforeach

                @php next($t1); @endphp

            @endforeach
            </tbody>
        </table>

    </div>

    <div>

        <table class="table">
            <thead class="thead-light">
            <tr>
                <th scope="col">Tabla 2</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($t2 as $mes)
                <thead class="thead-light">
                <tr>
                    <th scope="col">Mes: {{ key($t2) }}</th>
                </tr>
                <tr>
                    <th scope="col">Concepto</th>
                    <th scope="col">Row 0</th>
                    <th scope="col">Row 1</th>
                    <th scope="col">Row 2 - Total</th>
                </tr>
                </thead>
                @foreach ($mes as $concepto)
                    <tr>
                        <th scope="row">Tabla 2 | Mes: {{ key($t2) . ' | ' . key($mes) }}</th>
                        @foreach ($concepto as $key => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                    @php next($mes); @endphp

                    @endforeach

                    @php next($t2); @endphp

                    @endforeach
                    </tbody>
        </table>

    </div>
    
@endsection
