@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        
        <div class="col-md-2">
        <div class="row">
        <div class="col-md-12">
            <label>Filtros por Especie </label>
        </div>
        </div>
        <div class="row">
        <br>
        <div class="col-md-12">
            <label>Especie </label>
            {!! Form::select('especies', $especies, null, ['id'=>'especie', 'class' => 'form-control', 'placeholder' => 'Seleccione Especie']); !!}
        </div>
        </div>

        <div class="row">
        <div class="col-md-12">
        <label>Estado </label>
            {!! Form::select('estados', $estados, null, ['id'=>'estado', 'class' => 'form-control', 'placeholder' => 'Seleccione Estados']); !!}
        </div>
        </div>

        </div>
        <div class="col-md-10">
            <div id="map_canvas">
                <img src="images/loading.gif" style="width: 25px;height: 25px;">
            </div>
        </div>
        
    </div>
</div>
<style type="text/css">
    .container {
        padding-bottom: 90px;
    }

    #map_canvas{
        width: 100%;
        height: 100%;
        min-width: 350px;
        min-height: 500px;
    }
</style>
<script>

    var map;
    var select;
    var markers = [];
    var features = [
    @foreach ($arboles as $arbol)
    {
        id: {{$arbol->id}},
        position: {
            lat: {{ $arbol->lat }},
            long: {{ $arbol->long }}
        }
    },
    @endforeach
    ];

    function initialize() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 16,
            center: {lat: -38.717932, lng: -62.264621},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        for (var i = 0, feature; feature = features[i]; i++) {
          addMarker(feature);
      }
  }

  function setMapOnAll(map) {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

function addMarker(arbol) {
    var icon = {
        url: '{{url("images/tree.svg")}}',
        scaledSize: new google.maps.Size(25, 25), 
        origin: new google.maps.Point(0,0), 
        anchor: new google.maps.Point(0, 0) 
    };

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(arbol.position.lat, arbol.position.long),
        map: map,
        icon: icon,
        arbol: arbol
    });

    markers.push(marker);
}

function addActivityItem(id) {
    console.log(id);
    setMapOnAll(null);

    for (var i = 0, feature; feature = features[i]; i++) {
        if(feature.id == id)
          addMarker(feature);
  }
}

function loadScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.google.com/maps/api/js?callback=initialize";
    document.body.appendChild(script);

    select = document.getElementById('especie');

            /*select.addEventListener("click", function() {
                var options = select.querySelectorAll("option");
                var count = options.length;
                if(typeof(count) === "undefined" || count < 2)
                {
                    addActivityItem();
                }
            });*/

            select.addEventListener("change", function() {
                addActivityItem(select.value);
                /*if(select.value == "addNew")
                {
                    addActivityItem(select.value);
                }*/
            });
        }

        window.onload = loadScript;
    </script>
    @endsection