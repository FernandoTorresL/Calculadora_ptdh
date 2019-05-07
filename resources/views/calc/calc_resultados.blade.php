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
        <table class="table small">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tabla 1</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($tmptabla1 as $mes)
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Mes: {{ key($tmptabla1) }}</th>
                    @php
                        next($tmptabla1);
                    @endphp
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
                        {{--<th scope="row">{{ key($mes) . '|' . $key . ':' . $value }}</th>--}}
                            <th scope="row">{{ key($mes) }}</th>
                    @foreach ($concepto as $key => $value)
{{--                        @php--}}
{{--                            dd($mes);--}}
{{--                            dd(key($mes));--}}
{{--                        @endphp--}}
                            <td>{{ $value }}</td>

                    @endforeach
                        </tr>
                    @php
                        next($mes);
                    @endphp
                @endforeach
            @endforeach
            </tbody>
        </table>



    </div>
    
@endsection
