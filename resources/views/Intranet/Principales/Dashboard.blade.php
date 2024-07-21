<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/ventanasIntranet.css') }}" rel="stylesheet">

    <title>Dashboard</title>
</head>
<body>
    @include('Intranet/Principales/navbar')
    @include('Intranet/ErrorSucces')
    <div class="container">
        <div class="box">
            <h1 style="text-align: center">Ventas últimos 30 días</h1>
            <p style="text-align: center; font-size: 30px;">$ {{ number_format($s30d ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="box">
            <h1 style="text-align: center">Ventas últimos 60 días</h1>
            <p style="text-align: center; font-size: 30px;">$ {{ number_format($s60d ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="box">
            <h1 style="text-align: center">Ventas últimos 90 días</h1>
            <p style="text-align: center; font-size: 30px;">$ {{ number_format($s90d ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="container">
        <div class="box">
            <h1 style="text-align: center">Compras en proceso</h1>
            <p style="text-align: center; font-size: 30px;">{{ $cProceso ?? 0 }}</p>
        </div>
        <div class="box">
            <h1 style="text-align: center">Compras por entregar</h1>
            <p style="text-align: center; font-size: 30px;">{{ $pEntregar ?? 0 }}</p>
        </div>
        <div class="box">
            <h1 style="text-align: center">Compras entregadas</h1>
            <p style="text-align: center; font-size: 30px;">{{ $Entregadas ?? 0 }}</p>
        </div>
    </div>
    <div class="container">
        <div class="box">
            <h1 style="text-align: center">Producto más vendido sin oferta (Últimos 60 días)</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nombre del producto</th>
                        <th>Código de barra</th>
                        <th>Unidades vendidas</th>
                        <th>Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $materialVendido->nombrem ?? 'No se encuentra' }}
                        </td>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $materialVendido->codigob ?? 'No se encuentra' }}
                        </td>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $materialVendido->suma ?? 'No se encuentra' }}
                        </td>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <p>$ {{ number_format($materialVendido->valor ?? 0, 0, ',', '.') }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container">
        <div class="box">
            <h1 style="text-align: center">Producto más vendido con oferta (Últimos 60 días)</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nombre del producto</th>
                        <th>Código de barra</th>
                        <th>Unidades vendidas</th>
                        <th>Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $materialOfertaVendido->nombrem ?? 'No se encuentra' }}
                        </td>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $materialOfertaVendido->codigob ?? 'No se encuentra' }}
                        </td>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $materialOfertaVendido->suma ?? 'No se encuentra' }}
                        </td>
                        <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <p>$ {{ number_format($materialOfertaVendido->valor ?? 0, 0, ',', '.') }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
