<div class="row">
	<div class="col-md-6">
		<div class="col-md-12">
			<div class="form-group">
				<label>Especie</label>
				{!! Form::select('especie_id', $especies, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Especie']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Estado</label>
				{!! Form::select('estado', $estados, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Estado']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Tamaño Arbol</label>
				{!! Form::select('tamanio', $tamanios, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un Tamaño']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Diametro Tronco</label>
				{!! Form::select('diametro_tronco', $diametros, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una Diametro']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Ancho Vereda</label>
				{!! Form::select('ancho_vereda', $anchos_veredas, null, ['class' => 'form-control', 'placeholder' => 'Seleccione ancho vereda']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Tipo Vereda</label>
				{!! Form::select('tipo_vereda', $tipos_vereda, null, ['class' => 'form-control', 'placeholder' => 'Seleccione tipo de  vereda']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Cantero</label>
				{!! Form::select('cantero', $canteros, null, ['class' => 'form-control', 'placeholder' => 'Seleccione tipo tipo cantero']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Localidad</label>
				{!! Form::select('localidad_id', $localidades, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Localidad']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Dirección</label>
				{!! Form::select('calle_id', $calles, null, ['class' => 'form-control', 'placeholder' => 'Seleccione Dirección']); !!}
			</div>
		</div>

		<!--<div class="col-md-12">
			<div class="form-group">
				<label>Dirección</label>
				{!! Form::text('direccion', null, ['class' => 'form-control']) !!}
			</div>
		</div>-->

		<div class="col-md-12">
			<div class="form-group">
				<label>Altura</label>
				{!! Form::text('altura', null, ['class' => 'form-control']) !!}
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12">
			<label>Fotos</label>
			<div class="form-group">
				@foreach($censo->imagenes as $imagen)
				<div id="img-{{$imagen->id}}" class="imagen col-md-6" style="position:relative">
				<span class="stxt">#{{$imagen->id}}</span>
					<span class="borrarImagen" data-id="{{$imagen->id}}">x</span>
					<img src="{{ url($imagen->url) }}" style="width:100%">
				</div>
				@endforeach
			</div>
			<div class="form-group">
				{!! Form::file('image', ['class' => 'form-control']) !!}
			</div>
		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Guardar">
		<a href="{{ action('HomeController@index') }}" class="btn btn-default" title="">Cancelar</a>
	</div>
</div>
<style type="text/css">
.imagen {
    padding: 5px;
}
.imagen span.stxt {
    position: absolute;
    left: 5px;
    top: 5px;
    color: white;
    display: block;
}
	.imagen span{
		top: 5px;
		right: 5px;
		width: 25px;
		height: 25px;
		display: none;
		color: #0093ff;
		cursor: pointer;
		font-weight: bold;
		text-align: center;
		position: absolute;
		background-color: #a8dbff;
	}

	.imagen:hover span{
		display: block!important;
	}
</style>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$('.borrarImagen').on('click', function(){
			var id = $(this).data('id');
			bootbox.confirm("Desea eliminar la imagen N° " + id + " ?", function(result) {
				if(result){
					$.ajax({
						type: "DELETE",
						url: "{{ url('censos/imagen') }}/" + id,
						success: function (){
                            $('#img-'+id).remove();
                        },
						data: { _token: window.Laravel.csrfToken}
					});


				}
			});
		});
	});
</script>