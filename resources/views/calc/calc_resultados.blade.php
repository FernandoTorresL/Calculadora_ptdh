@extends('layouts.app')

@section('title')
    Calculadora PTdH
@endsection

@section('content')
    <div class="jumbotron text-center">
        <h1>Resultados!</h1>
        {{ $title_calc01 }}
    </div>

    @include('calc.tabla1')

    <br><br>

    @include('calc.tabla2')

    <br><br>

    @include('calc.tabla3')

@endsection
