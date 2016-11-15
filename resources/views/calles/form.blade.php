<div class="row">

		<div class="col-md-12">
			<div class="form-group">
				<label>Localidad</label>
				{!! Form::select('localidad_id', $localidades, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una localidad']); !!}
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label>Calle</label>
				{!! Form::text('nombre', null, ['class' => 'form-control']) !!}
			</div>
		</div>
</div>

<div class="row">
<div class="col-md-12">
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Guardar">
		<a href="{{ action('CallesController@index') }}" class="btn btn-default" title="">Cancelar</a>
	</div>
</div>
</div>