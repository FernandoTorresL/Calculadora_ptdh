@extends('layouts.app')

@section('title')
    Calculadora PTdH
@endsection

@section('content')
    <div class="jumbotron text-center">
        <h1>Calculadora PTdH</h1>
        <nav class="nav">
            <a class="nav-link active">Home</a>
            <a class="nav-link disabled" href="calculadora">Limpiar Calculadora</a>
        </nav>
    </div>

    <div class="row">
        <form action="/calculadora/calcula" method="post">
            @csrf

            @if ($errors->any())
                @php
                    //dd($errors);
                @endphp

                @foreach ($errors->get('nombre_patron') as $error)
                    <div class="text-danger">{{ $error }}</div>
                @endforeach

                @foreach ($errors->get('dias_laborados') as $error)
                    <div class="text-danger">{{ $error }}</div>
                @endforeach

                @foreach ($errors->get('sueldo_mensual') as $error)
                    <div class="text-danger">{{ $error }}</div>
                @endforeach
            @endif
            <div class="form-group">
                Nombre Patrón:
                <input type="text" name="nombre_patron" class="form-control" placeholder="Nombre del Patrón">

                Días laborados con este patrón:
                <input type="text" name="dias_laborados" class="form-control" placeholder="Días laborados">

                Sueldo mensual con este patrón:
                <input type="text" name="sueldo_mensual" class="form-control" placeholder="Sueldo mensual">
            </div>

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Agregar Patrón</button>
            </div>
            <div class="input-group text-left">
                <button type="" class="btn btn-info">Limpiar</button>
            </div>
        </form>
    </div>
@endsection
