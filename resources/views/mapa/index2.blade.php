@extends('layouts.app')

@section('content')
    <div id="map-container">
        <div id="loading_data" style="position: absolute; top: 0; left: 0; bottom: 0; display: none" class="col-md-10">
            <div class="loading">
                <img src="images/loading.gif" alt="">
            </div>
        </div>

        <div id="rigth-sidebar" class="col-md-2">
            <form id="form_filtros" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Filtros</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Especie </label>
                        {!! Form::select('especie', $especies, null, ['id'=>'especie', 'class' => 'form-control', 'placeholder' => 'Seleccione Especie']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Estado </label>
                        {!! Form::select('estado', $estados, null, ['id'=>'estado', 'class' => 'form-control', 'placeholder' => 'Seleccione Estados']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Tamaño </label>
                        {!! Form::select('tamanio', $tamanios, null, ['id'=>'tamanio', 'class' => 'form-control', 'placeholder' => 'Seleccione Tamaño']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Calle </label>
                        {!! Form::select('calle', $calles, null, ['id'=>'calle', 'class' => 'form-control', 'placeholder' => 'Seleccione Calle']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>Altura Min.</label>
                        {!! Form::text('altura_min', null, ['id'=>'altura_min', 'class' => 'form-control', 'placeholder' => 'Altura Min']) !!}

                        <label>Altura Max.</label>
                        {!! Form::text('altura_max', null, ['id'=>'altura_max', 'class' => 'form-control', 'placeholder' => 'Altura Max']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button id="filtrar" href="#" class="btn btn-primary btn-sm">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="map" style="width:100%;height: 700px;"></div>
        <hr>
        <div id="detalle" class="row" style="padding: 25px;"></div>
    </div>
    <style type="text/css" media="screen">

        .loading {
            top: 50%;
            left: 50%;
            width: 200px;
            height: 100px;
            z-index: 1001;
            margin-left: -100px;
            margin-top: -50px;
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
        }

        .loading img {
            position: absolute;
            width: 25px;
            height: 25px;
            top: 50%;
            left: 50%;
            margin-left: -12.5px;
            margin-top: -12.5px;
        }

        #map-container {
            position: relative;
        }

        #rigth-sidebar {
            position: absolute;
            right: 15px;
            top: 15px;
            background: white;
            z-index: 1000;
            border: 5px solid #e6e3e3;
        }

        #rigth-sidebar h4 {
            text-align: center;
            border-bottom: 1px solid silver;
        }

        div#rigth-sidebar div.row {
            margin-bottom: 10px;
        }

        select.form-control {
            height: 28px;
            padding: 2px 4px;
        }
    </style>

    <script src="{{ url('js/markerclusterer.js') }}"></script>
    <script>

        var map;
        var infoWindow;
        var markers = [];
        var markerClusters = [];
        var baseURL = "{{ url('/') }}";

        function initialize() {
            var center = new google.maps.LatLng(-38.7183038, -62.2642266);

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: center,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            infoWindow = new google.maps.InfoWindow();

            $.getJSON(baseURL + '/api/censos', function (data) {

                $.each(data.data, function (i, value) {
                    var myLatlng = new google.maps.LatLng(value.lat, value.long);
                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        content: value
                    });

                    google.maps.event.addListener(marker, 'click', function () {
                        var html = '<div><div><label>' + this.content.especie.nombre + '</label></div>' +
                                '<div style="text-align:center"><img src="http://arboladoapp.bahiablanca.gob.ar/' + (this.content.imagenes[0] ? this.content.imagenes[0].url : '') + '" style="width100%; max-width:200px; margin-bottom: 10px;"></div>' +
                                '<div><label>Dirección:</label> ' + this.content.direccion + '</div>' +
                                '<div><label>Altura:</label> ' + this.content.altura + '</div>' +
                                '</div>';
                        infoWindow.setContent(html);
                        infoWindow.open(this.getMap(), this);

                        var detalle =
                                '<div class="panel col-md-12" style="background: #f7f7f7;">' +
                                '<h3>Datos del censo</h3>' +
                                '<p><label>Especie:</label> '  + this.content.especie.nombre + '</p>' +
                                '<p><label>Estado:</label> '  + this.content.estado + '</p>' +
                                '<p><label>Tamaño:</label> '  + this.content.tamanio + '</p>' +
                                '<p><label>Diametro tronco:</label> '  + this.content.diametro_tronco + '</p>' +
                                '<p><label>Ancho vereda:</label> '  + this.content.ancho_vereda + '</p>' +
                                '<p><label>Tipo vereda:</label> '  + this.content.tipo_vereda + '</p>' +
                                '<p><label>Cantero:</label> '  + this.content.cantero + '</p>' +
                                '<p><label>Dirección:</label> '  + this.content.direccion + '</p>' +
                                '<p><label>Observaciones:</label> '  + this.content.observaciones + '</p></div>';

                        $('#detalle').html(detalle);
                    });

                    console.log(value);
                    if (!markers[value.especie_id]) {
                        markers[value.especie_id] = []
                    }
                    markers[value.especie_id].push(marker);
                });

                for (var i = 0; i < markers.length; i++) {
                    var options = {
                        imagePath: 'images/m'
                    };
                    markerClusters.push(new MarkerClusterer(map, markers[i], options));
                }
            });
        }

        $('#form_filtros').on('submit', function (e) {
            e.preventDefault();
            $('#loading_data').show();

            var settings = {
                "url": baseURL + "/api/mapa_ll",
                "method": "POST",
                "headers": {
                    "cache-control": "no-cache"
                },
                "data": $(this).serialize()
            };

            $.ajax(settings).done(function (response) {
                console.log(response);

                markers = [];

                for (var i = 0; i < markerClusters.length; i++) {
                    markerClusters[i].clearMarkers();
                }

                $.each(response, function (i, value) {
                    var myLatlng = new google.maps.LatLng(value.lat, value.long);
                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        content: value
                    });

                    google.maps.event.addListener(marker, 'click', function () {
                        var html = '<div><div><label>' + this.content.especie.nombre + '</label></div>' +
                                '<div style="text-align:center"><img src="{{ url('/') }}/' + (this.content.imagenes[0] ? this.content.imagenes[0].url : '') + '" style="width:100%; max-width:200px; margin-bottom: 10px;"></div>' +
                                '<div><label>Dirección:</label> ' + this.content.direccion + '</div>' +
                                '<div><label>Altura:</label> ' + this.content.altura + '</div>' +
                                '</div>';
                        infoWindow.setContent(html);
                        infoWindow.open(this.getMap(), this);

                        var detalle =
                                '<div class="panel col-md-12" style="background: #f7f7f7;">' +
                                '<h3>Datos del censo</h3>' +
                                '<p><label>Especie:</label> '  + this.content.especie.nombre + '</p>' +
                                '<p><label>Estado:</label> '  + this.content.estado + '</p>' +
                                '<p><label>Tamaño:</label> '  + this.content.tamanio + '</p>' +
                                '<p><label>Diametro tronco:</label> '  + this.content.diametro_tronco + '</p>' +
                                '<p><label>Ancho vereda:</label> '  + this.content.ancho_vereda + '</p>' +
                                '<p><label>Tipo vereda:</label> '  + this.content.tipo_vereda + '</p>' +
                                '<p><label>Cantero:</label> '  + this.content.cantero + '</p>' +
                                '<p><label>Dirección:</label> '  + this.content.direccion + '</p>' +
                                '<p><label>Observaciones:</label> '  + this.content.observaciones + '</p></div>';

                        $('#detalle').html(detalle);
                    });

                    if (!markers[value.especie_id]) {
                        markers[value.especie_id] = []
                    }
                    markers[value.especie_id].push(marker);
                });


                for (i = 0; i < markers.length; i++) {
                    var options = {
                        imagePath: 'images/m'
                    };
                    markerClusters.push(new MarkerClusterer(map, markers[i], options));
                }

                $('#loading_data').hide();
            }).fail(function () {
                $('#loading_data').hide();
            });
        });

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY') }}&&callback=initialize"></script>

@endsection