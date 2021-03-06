@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">

		<div class="col-md-6">
			<div class="row">
				<div class="col-md12">
					<h3>Datos del arbol</h3>
					<p><label>Especie:</label> {{ $arbol->especie->nombre }}</p>
					<p><label>Estado:</label> {{ $arbol->estado }}</p>
					<p><label>Tamaño:</label> {{ $arbol->tamanio }}</p>
					<p><label>Diametro tronco:</label> {{ $arbol->diametro_tronco }}</p>
					<p><label>Ancho vereda:</label> {{ $arbol->ancho_vereda }}</p>
					<p><label>Tipo vereda:</label> {{ $arbol->tipo_vereda }}</p>
					<p><label>Cantero:</label> {{ $arbol->cantero }}</p>		
					<p><label>Dirección:</label> {{ $arbol->direccion }} - {{ $arbol->altura }}</p>
					<p><label>Observaciones:</label> {{ $arbol->observaciones }}</p>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
				<h3>Ubicación en el mapa</h3>
					<div id="map_canvas"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

				</div>
			</div>
		</div>

	</div>
	<div class="row">
			<h3>Fotos del árbol</h3>
			@foreach($arbol->imagenes as $imagen)
			<a class="fancybox" rel="group" href="{{ url($imagen->url) }}"><img src="{{ url($imagen->url) }}" alt="" /></a>
			@endforeach
		
	</div>
</div>
<style type="text/css">
	a.fancybox img {
	    width: 100%;
	    max-width: 250px;
	}

	a.fancybox {
	    margin: 5px;
	    border: 4px solid #eae8e8;
	    border-radius: 3px;
	    overflow: hidden;
	    width: 250px;
	    display: inline-block;
	}

	#map_canvas{
		width: 100%;
		height: 100%;
		min-width: 350px;
		min-height: 350px;
	}
</style>


<script type="text/javascript" src="/lib/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>

<script>
	function initialize() {
		var myLatLng = {lat: {{ $arbol->lat }}, lng: {{ $arbol->long }}};

		var icon = {
			url: '{{url("images/tree.svg")}}',
			scaledSize: new google.maps.Size(25, 25), 
			origin: new google.maps.Point(0,0), 
			anchor: new google.maps.Point(0, 0) 
		};

		var map = new google.maps.Map(document.getElementById('map_canvas'), {
			zoom: 16,
			center: myLatLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			title: 'Árbol: {{ $arbol->especie->nombre}}',
			icon: icon
		});
	}

	function loadScript() {
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://maps.google.com/maps/api/js?callback=initialize";
		document.body.appendChild(script);
	}

	window.onload = loadScript;


</script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
@endsection