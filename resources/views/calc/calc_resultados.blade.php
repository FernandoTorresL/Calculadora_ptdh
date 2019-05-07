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

        <table class="table small table-active">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tabla 1</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tablas_calculos1 as $mes)
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Mes: {{ key($tablas_calculos1) }}</th>
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
                            <th scope="row">Tabla 1 | Mes: {{ key($tablas_calculos1) . ' | ' . key($mes) }}</th>
                    @foreach ($concepto as $key => $value)
                            <td>{{ $value }}</td>
                    @endforeach
                        </tr>
                    @php next($mes); @endphp

                @endforeach

                @php next($tablas_calculos1); @endphp

            @endforeach
            </tbody>
        </table>

    </div>

    <div>

        <table class="table small table-success">
            <thead class="thead-light">
            <tr>
                <th scope="col">Tabla 2</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tablas_calculos2 as $mes)
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Mes: {{ key($tablas_calculos2) }}</th>
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
                        <th scope="row">Tabla 2 | Mes: {{ key($tablas_calculos2) . ' | ' . key($mes) }}</th>
                        @foreach ($concepto as $key => $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                    @php next($mes); @endphp

                    @endforeach

                    @php next($tablas_calculos2); @endphp

                    @endforeach
                    </tbody>
        </table>

    </div>
    
@endsection
