@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Especies <a href="{{ action('EspeciesController@create') }}" style="float:right" class="btn btn-sm btn-primary">Nueva Especie</a></h4></div>

                <div class="panel-body">
                    @include('flash::message')
                    <table class="table">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th width="120">Acciones</th>
                        </tr>
                        @foreach($especies as $especie)
                        <tr id="row-{{ $especie->id }}">
                            <td>{{ $especie->id }}</td>
                            <td>{{ $especie->nombre }}</td>
                            <td>
                                <a href="{{ action('EspeciesController@edit', $especie->id) }}" style="font-size: 18px; color: #00c2ff;" title="">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#" style="font-size: 18px; color: red;" title="" onclick="aprobarItem({{ $especie->id }})">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {{ $especies->links() }}
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

    function aprobarItem(id) {

        bootbox.confirm("Desea aprobar el registro N° " + id + " ?", function(result) {
            if(result){
              $.ajax({
                type: "PUT",
                url: "{{ url('especies') }}" + id + "/aprobar",
                success: function (){
                    $('#row-'+id).remove();
                },
                data: { _token: window.Laravel.csrfToken}
            });


        }
    });         
    }
    function deleteItem(id) {

        bootbox.confirm("Desea eliminar el registro N° " + id + " ?", function(result) {
            if(result){
              $.ajax({
                type: "DELETE",
                url: "{{ url('especies') }}/" + id,
                success: function (){
                    $('#row-'+id).remove();
                },
                data: { _token: window.Laravel.csrfToken}
            });


        }
    });         
    }
</script>
@endsection