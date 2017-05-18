@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Cambiar Contreseña</div>

                <div class="panel-body">
                    <div class="alert alert-info">
                        <h4>Contraseña valida</h4>
                        <ul>
                            <li>Debe tener una longitud de 8 a 30 caracteres</li>
                            <li>Debe contener al menos un número y una letra mayuscula.</li>
                            <li>Se aceptan los caracteres ( _ y . )</li>
                        </ul>
                    </div>
                    @include('vendor.flash.message')
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/change') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('current_pwd') ? ' has-error' : '' }}">
                            <label for="current_pwd" class="col-md-4 control-label">Contreseña Actual</label>

                            <div class="col-md-6">
                                <input id="current_pwd" type="password" class="form-control" name="current_pwd" required>

                                @if ($errors->has('current_pwd'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_pwd') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Nueva Contreseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmar Contreseña</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
