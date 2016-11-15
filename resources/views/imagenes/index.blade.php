@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Censo</th>
                            <th>Imagen</th>
                            <th width="120">Acciones</th>
                        </tr>
                        @foreach($imagenes as $imagen)
                        <tr id="row-{{ $imagen->id }}">
                            <td>{{ $imagen->id }}</td>
                            <td>{{ $imagen->id }}</td>
                            <td>{{ $imagen->censo ? $imagen->censo->id }}</td>
                            <td>{{ $imagen->id }}</td>
                            <td>
                                <a href="#" style="font-size: 18px; color: red;" title="" onclick="aprobarItem({{ $imagen->id }})">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    {{ $imagen->links() }}
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
                url: "{{ url('especies') }}/" + id,
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
