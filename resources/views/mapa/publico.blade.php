<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="/lib/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen"/>
    <script src="{{ asset('js/bootbox.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">

    <style type="text/css">

        html, body, #map-container, #map {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map {
            position: relative;
        }

        .panel-default > .panel-heading {
            color: white;
            background-color: #4c4c4c;
            border: 1px solid #4c4c4c;
        }

        .panel-default {
            border-color: #969494;
        }

        .filtros{
            display: none;
            overflow: hidden;
            margin-top: 10px;
        }

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

        #rigth-sidebar h4 {
            text-align: center;
            border-bottom: 1px solid silver;
        }

        #rigth-sidebar {
            top: 15px;
            right: 15px;
            z-index: 1000;
            position: absolute;
            padding-bottom: 15px;
            border: 5px solid #e6e3e3;
            background: rgba(255, 255, 255, 0.70);
        }

        select.form-control {
            height: 28px;
            padding: 2px 4px;
        }
    </style>
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="map-container">
    <div id="loading_data" style="position: absolute; top: 0; left: 0; bottom: 0; display: none" class="col-md-10">
        <div class="loading">
            <img src="images/loading.gif" alt="">
        </div>
    </div>

    <div id="rigth-sidebar" class="col-md-2">
        <form id="form_filtros" method="POST">
            <div id="toggle-filtros" class="row" style="cursor: pointer;">
                <div class="col-md-12">
                    <h4>Filtros</h4>
                </div>
            </div>
            <div class="filtros">
                <div class="row">
                    <div class="col-md-12">
                        <label>Especie </label>
                        {!! Form::select('especie', $especies, null, ['id'=>'especie', 'class' => 'form-control', 'placeholder' => 'Seleccione Especie']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Calle </label>
                        {!! Form::select('calle', $calles, null, ['id'=>'calle', 'class' => 'form-control', 'placeholder' => 'Seleccione Calle']) !!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <button id="filtrar" href="#" class="btn btn-primary btn-sm">Filtrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="map" style="width:100%;"></div>
</div>

<script src="{{ url('js/markerclusterer.js') }}"></script>
<script>

    var map;
    var infoWindow;
    var markers = [];
    var markerClusters = [];
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
                            '</div>';
                    infoWindow.setContent(html);
                    infoWindow.open(this.getMap(), this);
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

    $('#toggle-filtros').on('click',function (e) {
        $('.filtros').toggle();
    });

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
</body>
</html>