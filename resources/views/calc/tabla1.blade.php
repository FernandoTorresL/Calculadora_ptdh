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
