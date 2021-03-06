@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Calles <a href="{{ action('CallesController@create') }}"
                                                             style="float:right" class="btn btn-sm btn-primary">Nueva
                                Calle</a></h4></div>

                    <div class="panel-body">
                        @include('flash::message')
                        <div class="row">
                            <form method="GET">
                                <div class="col-md-10 col-sm-10 col-xs-8">
                                    <div class="control-group">
                                        <input type="text"
                                               id="search"
                                               class="form-control"
                                               name="q"
                                               value="{{ request()->get('q') }}"
                                               placeholder="Búscar"
                                               autocomplete="off"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-4">
                                    <div class="control-group">
                                        <a href="{{ action('CallesController@index') }}" id="reset"
                                           class="btn btn-default">Resetear</a>
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
                                        @if(!auth()->user()->isAdmin())
                                            <a href="javascript:void(0)" style="font-size: 18px; color: silver;">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" style="font-size: 18px; color: silver;">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <a href="{{ action('CallesController@edit', $calle->id) }}"
                                               style="font-size: 18px; color: #00c2ff;" title="">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="#" style="font-size: 18px; color: red;" title=""
                                               onclick="deleteItem({{ $calle->id }})">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        @endif
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
    @if(!auth()->user()->isAdmin())
    <script type="text/javascript" charset="utf-8">
        function deleteItem(id) {

            bootbox.confirm("Desea eliminar el registro N° " + id + " ?", function (result) {
                if (result) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('calles') }}/" + id,
                        success: function () {
                            $('#row-' + id).remove();
                        },
                        data: {_token: window.Laravel.csrfToken}
                    });


                }
            });
        }
    </script>
    @endif
@endsection