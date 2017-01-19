@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Eventos por estado</h4></div>
                    <div class="panel-body">

                        <!-- create container element for visualization -->
                        <div class="row">
                            <div class="col-md-6">
                                <div id="estados" style="height: 250px"></div>
                            </div>
                            <div class="col-md-6">
                                <table class="table" style="margin-top: 50px;">
                                    <tr style="background: #e2e2e2;">
                                        <td><b>Censos Totales</b></td>
                                        <td><b>{{ $censos->count() }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>Estado Bueno</td>
                                        <td>{{ $censos->where('estado', \App\Censo::ESTADO_BUENO)->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Estado Regular</td>
                                        <td>{{ $censos->where('estado', \App\Censo::ESTADO_REGULAR)->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Estado Malo</td>
                                        <td>{{ $censos->where('estado', \App\Censo::ESTADO_MALO)->count() }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Top 15 de especies</h4></div>
                    <div class="panel-body">
                        <div id="barras" style="height: 250px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')

    <script src="{{ asset('js/d3plus.v1.9.8/d3.js') }}"></script>
    <script src="{{ asset('js/d3plus.v1.9.8/d3plus.js') }}"></script>
    <script>

        // sample data array
        var data = [
            {
                "value": {{ $censos->where('estado', \App\Censo::ESTADO_BUENO)->count() }},
                "name": "Bueno",
                "hex": "#22d431"
            },
            {
                "value": {{ $censos->where('estado', \App\Censo::ESTADO_REGULAR)->count() }},
                "name": "Regular",
                "hex": "#e47a05"
            },
            {
                "value": {{ $censos->where('estado', \App\Censo::ESTADO_MALO)->count() }},
                "name": "Malo",
                "hex": "#ea000b"
            }
        ];

        var attr1 = [
            {"name": "Bueno", "hex": "#22d431"},
            {"name": "Regular", "hex": "#e47a05"},
            {"name": "Malo", "hex": "#ea000b"}

        ];

        d3plus.viz()
            .container("#estados")
            .data(data)
            .type("pie")
            .id("name")
            .size("value")
            .attrs(attr1)
            .color("hex")
            .format({
                "text": function (text, params) {
                    console.log(text);
                    console.log(params);
                    if (text === "value") {
                        return "Cantidad Eventos";
                    } else if (text === "share") {
                        return "Porcentaje";
                    } else {
                        return d3plus.string.title(text, params);
                    }
                }
            })
            .draw();

        var data1 = {!! json_encode($barras) !!};

        var visualization = d3plus.viz()
            .container("#barras")
            .data(data1)
            .type("bar")
            .id("nombre")
            .x("id")
            .y("total")
            .draw()
    </script>
@endsection