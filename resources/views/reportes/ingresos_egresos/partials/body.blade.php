
<div class="div_items" style="width:100%">
    <table class="table_items" id="table_venta" width="100%">

        <tr class="tr-header">
            <td><strong>Nº Doc.</strong></td>
            <td><strong>Nombres</strong></td>
            <td><strong>Fecha</strong></td>
            <td><strong>S./</strong></td>
            <td><strong>$./</strong></td>
            <td><strong>Motivo</strong></td>
            <td><strong>Usuario</strong></td>
        </tr>
        <tbody>
            @foreach ($items['items'] as $item)
                <tr class="tr-body">
                    <td>{{ $item->id }} </td>
                    <td>{{ $item->nombre }} </td>
                    <td>{{ $item->fecha }} </td>
                    <td style="text-align:right">{{ fixedValue($item->soles) }} </td>
                    <td style="text-align:right">{{ fixedValue($item->dolares) }} </td>
                    <td>{{ $item->motivo }} </td>
                    <td>{{ $item->usuario }} </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #ccc" class="tr-body">
                <td></td>
                <td>Total S./ </td>
                <td> <strong> {{  fixedValue($items['totalSoles']) }} </strong> </td>
                <td></td>
                <td>Total $./ </td>
                <td> <strong> {{ fixedValue($items['totalDolares']) }} </strong> </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

{{--  --}}
