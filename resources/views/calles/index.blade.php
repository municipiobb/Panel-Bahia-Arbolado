@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Calles <a href="{{ action('CallesController@create') }}" style="float:right" class="btn btn-sm btn-primary">Nueva Calle</a></h4></div>

                <div class="panel-body">
                    @include('flash::message')
                    <div class="row">
                    <form method="GET">
                        <div class="col-md-10">
                            <div class="control-group">
                                <input type="text" id="search" class="form-control" name="q" placeholder="Búscar" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="control-group">
                                <a href="{{ action('CallesController@index') }}" id="reset" class="btn btn-default">Resetear</a>
                            </div>
                        </div>
                    </form>
                    </div>
                    <br>
                    <table class="table">
                        <tr>
                            <th>#</th>
                            <th>Localidad</th>
                            <th>Nombre</th>
                            <th width="120">Acciones</th>
                        </tr>
                        @foreach($calles as $calle)
                        <tr id="row-{{ $calle->id }}">
                            <td>{{ $calle->id }}</td>
                            <td>{{ Helpers::getLocalidad($calle->localidad_id) }}</td>
                            <td>{{ $calle->nombre }}</td>
                            <td>
                                <a href="{{ action('CallesController@edit', $calle->id) }}" style="font-size: 18px; color: #00c2ff;" title="">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#" style="font-size: 18px; color: red;" title="" onclick="deleteItem({{ $calle->id }})">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {{ $calles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css" media="screen">
    .modal-body {
        color: #ff7676;
        font-size: 18px;
        font-weight: bold;
    }
</style>
<script type="text/javascript" charset="utf-8">
    function deleteItem(id) {

        bootbox.confirm("Desea eliminar el registro N° " + id + " ?", function(result) {
            if(result){
              $.ajax({
                type: "DELETE",
                url: "{{ url('calles') }}/" + id,
                success: success,
                data: { _token: window.Laravel.csrfToken}
            });

              function success(res){
                $('#row-'+id).remove();
            }
        }
    });         
    }
</script>
@endsection