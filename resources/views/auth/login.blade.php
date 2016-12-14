@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <!-- <label for="email" class="col-md-4 control-label">E-Mail Address</label> -->

                            <div class="col-md-12">
                                <input id="name" 
                                type="text" 
                                class="form-control" 
                                name="name" 
                                value="{{ old('name') }}" 
                                placeholder="Usuario" 
                                required 
                                autofocus
                                >

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <!-- <label for="password" class="col-md-4 control-label">Password</label> -->

                            <div class="col-md-12">
                                <input id="password" 
                                type="password" 
                                class="form-control" 
                                name="password" 
                                placeholder="Contraseña"  
                                required
                                >

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width:100%;font-size: 18px;">
                                    Login
                                </button>
                            </div>
                        </div>

                        <a class="btn btn-link" href="{{ url('/password/reset') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css" media="screen">
    .form-control{
        border-radius: 0;
    }
</style>
@endsection
