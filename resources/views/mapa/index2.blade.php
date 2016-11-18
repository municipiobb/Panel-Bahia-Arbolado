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
                        {!! Form::select('especie', $especies, null, ['id'=>'especie', 'class' => 'form-control', 'placeholder' => 'Seleccione Especie']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Estado </label>
                        {!! Form::select('estado', $estados, null, ['id'=>'estado', 'class' => 'form-control', 'placeholder' => 'Seleccione Estados']); !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>Tamaño </label>
                        {!! Form::select('tamanio', $tamanios, null, ['id'=>'tamanio', 'class' => 'form-control', 'placeholder' => 'Seleccione Tamaño']); !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button id="filtrar" href="#" class="btn btn-primary btn-sm">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="map" style="width:100%;height: 450px;"></div>
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
            bottom: 15px;
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

    <script src="js/markerclusterer.js"></script>
    <script>

        var markers = [];
        var markerClusters = [];
        var infoWindow;
        var map;
        function initialize() {
            var center = new google.maps.LatLng(-38.7183038, -62.2642266);

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: center,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            infoWindow = new google.maps.InfoWindow();

            $.getJSON('http://arboladoapp.bahiablanca.gob.ar/api/censos', function (data) {

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
                                '</div>'
                        infoWindow.setContent(html);
                        infoWindow.open(this.getMap(), this);
                    });

                    console.log(value);
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
            });
        }

        $('#form_filtros').on('submit', function (e) {
            e.preventDefault();
            $('#loading_data').show();

            var settings = {
                "url": "http://arboladoapp.bahiablanca.gob.ar/api/mapa_ll",
                "method": "POST",
                "headers": {
                    "cache-control": "no-cache"
                },
                "data": $(this).serialize()
            }

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
                                '<div style="text-align:center"><img src="http://arboladoapp.bahiablanca.gob.ar/' + (this.content.imagenes[0] ? this.content.imagenes[0].url : '') + '" style="width100%; max-width:200px; margin-bottom: 10px;"></div>' +
                                '<div><label>Dirección:</label> ' + this.content.direccion + '</div>' +
                                '<div><label>Altura:</label> ' + this.content.altura + '</div>' +
                                '</div>'
                        infoWindow.setContent(html);
                        infoWindow.open(this.getMap(), this);
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
            }).fail(function (response) {
                $('#loading_data').hide();
            });
        });


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvUN-CRtMA8YD1vDpGFpt45VOuJXWIyzo&callback=initialize"></script>

@endsection