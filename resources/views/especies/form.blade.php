
<div class="form-group">
    <label>Nombre</label>
    {!! Form::text('nombre', null, ['class' => 'form-control', 'autofocus'=>'autofocus']) !!}
</div>

<br>

<div class="form-group">

    <input type="submit" class="btn btn-primary" value="Guardar">
    <a href="{{ action('EspeciesController@index') }}" class="btn btn-default" title="">Cancelar</a>
</div>
