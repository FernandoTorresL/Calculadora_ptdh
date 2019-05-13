<div>
    <h3>Tabla 03 - Valores proporcionales y totales por mes y por patrón</h3>
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
