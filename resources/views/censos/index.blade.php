@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Listado de censos</div>

                    <div class="panel-body">
                        @include('flash::message')
                        <div class="col-md-12">
                            <p>Filtros</p>
                        </div>
                        <form action="" method="get" accept-charset="utf-8">

                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <label>Especie</label>
                                        <select id="especies" class="especies" name="especie" style="width:100%">
                                            <option value="0">Especie</option>
                                            @foreach($especies as $key => $value)
                                                <option value="{{$key}}" {{ request()->especie == $key ? 'selected': ''}}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="estado">Estado</label>
                                        <select id="estado" class="estado" name="estado" style="width:100%">
                                            <option value="">Estado</option>
                                            <option value="Bueno" {{ request()->estado == 'Bueno' ? 'selected': ''}}>
                                                Bueno
                                            </option>
                                            <option value="Regular" {{ request()->estado == 'Redular' ? 'selected': ''}}>
                                                Regular
                                            </option>
                                            <option value="Malo" {{ request()->estado == 'Malo' ? 'selected': ''}}>
                                                Malo
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tamanio">Tamaño árbol</label>
                                        <select id="tamanio" class="tamanio" name="tamanio" style="width:100%">
                                            <option value="">Tamaño</option>
                                            <option value="Chico" {{ request()->tamanio == 'Chico' ? 'selected': ''}}>
                                                Chico
                                            </option>
                                            <option value="Mediano" {{ request()->tamanio == 'Mediano' ? 'selected': ''}}>
                                                Mediano
                                            </option>
                                            <option value="Grande" {{ request()->tamanio == 'Grande' ? 'selected': ''}}>
                                                Grande
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="diametro">Diametro Tronco</label>
                                        <select id="diametro" class="diametro" name="diametro" style="width:100%">
                                            <option value="">Diametro</option>
                                            <option value="-30 cm" {{ request()->diametro == '-30 cm' ? 'selected': ''}}>
                                                -30
                                                cm
                                            </option>
                                            <option value="+50 cm" {{ request()->diametro == '+50 cm' ? 'selected': ''}}>
                                                +50
                                                cm
                                            </option>
                                            <option value="30-50 cm" {{ request()->diametro == '30-50 cm' ? 'selected': ''}}>
                                                30-50 cm
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="ancho_vereda">Ancho Vereda</label>
                                        <select id="ancho_vereda" class="ancho_vereda" name="ancho_vereda"
                                                style="width:100%">
                                            <option value="">Ancho</option>
                                            <option value="-1.5 mts" {{ request()->ancho_vereda == '-1.5 mts' ? 'selected': ''}}>
                                                -1.5 mts
                                            </option>
                                            <option value="+3.5 mts" {{ request()->ancho_vereda == '+3.5 mts' ? 'selected': ''}}>
                                                +3.5 mts
                                            </option>
                                            <option value="1.5-3.5 mts" {{ request()->ancho_vereda == '1.5-3.5 mts' ? 'selected': ''}}>
                                                1.5-3.5 mts
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="tipo_vereda">Tipo Vereda</label>
                                        <select id="tipo_vereda" class="tipo_vereda" name="tipo_vereda"
                                                style="width:100%">
                                            <option value="">Tipo</option>
                                            <option value="Baldosa" {{ request()->tipo_vereda == 'Baldosa' ? 'selected': ''}}>
                                                Baldosa
                                            </option>
                                            <option value="Tierra" {{ request()->tipo_vereda == 'Tierra' ? 'selected': ''}}>
                                                Tierra
                                            </option>
                                            <option value="Tierra y cesped" {{ request()->tipo_vereda == 'Tierra y cesped' ? 'selected': ''}}>
                                                Tierra y cesped
                                            </option>

                                            <option value="Tierra, cesped y baldosa" {{ request()->tipo_vereda == 'Tierra, cesped y baldosa' ? 'selected': ''}}>
                                                Tierra, cesped y baldosa
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <label>Calle </label>
                                        {!! Form::select('calle', $calles, request()->calle, ['id'=>'calle', 'class' => 'form-control', 'placeholder' => 'Seleccione Calle']) !!}
                                    </div>

                                    <div class="col-md-2">
                                        <label>Altura Min.</label>
                                        {!! Form::text('altura_min', request()->altura_min, ['id'=>'altura_min', 'class' => 'form-control', 'placeholder' => 'Altura Min']) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <label>Altura Max.</label>
                                        {!! Form::text('altura_max', request()->altura_max, ['id'=>'altura_max', 'class' => 'form-control', 'placeholder' => 'Altura Max']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-sm btn-primary" style="margin-top: 25px;">Buscar</button>
                                <a class="btn btn-sm btn-default" style="margin-top: 25px;"
                                   href="{{url('/')}}">Limpiar</a>
                            </div>
                        </form>

                        <table class="table">
                            <caption>
                                <p>
                                <h4 style="float: left;">Total de árboles filtrados: {{ $censos->total() }}</h4>
                                <div style="float: right;">
                                    <i class="fa fa-pencil" aria-hidden="true"></i> (Editar) | <i class="fa fa-eye"
                                                                                                  aria-hidden="true"></i>
                                    (Ver) | <i class="fa fa-check" aria-hidden="true"></i> (Aprobar)</p>
                                </div>
                            </caption>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Especie</th>
                                <th>Estado</th>
                                <th>Tamaño Árbol</th>
                                <th>Diametro Tronco</th>
                                <th>Ancho Vereda</th>
                                <th>Tipo Vereda</th>
                                <th>Cantero</th>
                                <th>Dirección</th>
                                <th>Fotos</th>
                                <th>Fecha</th>
                                <th width="100">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($censos as $censo)
                                <tr id="row-{{$censo->id}}">
                                    <td>{{ $censo->id }}</td>
                                    <td>{{ $censo->especie->nombre }}</td>
                                    <td class="censo-{{ strtolower($censo->estado) }}">
                                        {{ $censo->estado }}
                                    </td>
                                    <td>{{ $censo->tamanio }}</td>
                                    <td>{{ $censo->diametro_tronco }}</td>
                                    <td>{{ $censo->ancho_vereda }}</td>
                                    <td>{{ $censo->tipo_vereda }}</td>
                                    <td>{{ $censo->cantero }}</td>
                                    <td>{{ ucwords(strtolower($censo->calle->nombre)) }} {{ $censo->altura }}</td>
                                    <td>{{ $censo->imagenes()->count() }}</td>
                                    <td>{{ $censo->created_at->format('d-m-Y H:i')}}</td>
                                    <td>
                                        @if(!auth()->user()->isAdmin())
                                            <a href="javascript:void(0)" title="Editar"
                                               style="font-size: 18px; color: silver;">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" title="Ver"
                                               style="font-size: 18px; color: silver;">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" style="font-size: 18px; color: silver;"
                                               title="Borrar">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <a href="{{ action('CensosController@edit', $censo->id) }}" title="Editar"
                                               style="font-size: 18px; color: #00c2ff;">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ action('CensosController@show', $censo->id) }}" title="Ver"
                                               style="font-size: 18px; color: #ff9b00;">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a id="borrar-{{$censo->id}}" href="#"
                                               style="font-size: 18px; color: #ef0000;"
                                               title="Borrar" onclick="borrarItem({{ $censo->id }})">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                            @if(!$censo->status)
                                                <a id="aprobar-{{$censo->id}}" href="#"
                                                   style="font-size: 18px; color: green;" title="Aprobar"
                                                   onclick="aprobarItem({{ $censo->id }})">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $censos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css" media="screen">

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #ccd0d2;
        }

        .select2-container .select2-selection--single {
            height: 35px;
        }

        .censo-bueno {
            color: green;
        }

        .censo-regular {
            color: orange;
        }

        .censo-malo {
            color: red;
        }
    </style>
@endsection

@section('scripts')

    @if(!auth()->user()->isAdmin())
        <script type="text/javascript" charset="utf-8">

            $(document).ready(function () {
                $("#especies").select2();
                $("#estado").select2();
                $("#tamanio").select2();
                $("#diametro").select2();
                $("#ancho_vereda").select2();
                $("#tipo_vereda").select2();
            });
        </script>
    @else
        <script type="text/javascript" charset="utf-8">

            $(document).ready(function () {
                $("#especies").select2();
                $("#estado").select2();
                $("#tamanio").select2();
                $("#diametro").select2();
                $("#ancho_vereda").select2();
                $("#tipo_vereda").select2();
            });


            function aprobarItem(id) {

                bootbox.confirm("Desea aprobar el registro N° " + id + " ?", function (result) {
                    if (result) {
                        $.ajax({
                            type: "PUT",
                            url: "{{ url('censos') }}/" + id + "/aprobar",
                            success: function () {
                                $('#aprobar-' + id).remove();
                            },
                            data: {_token: window.Laravel.csrfToken}
                        });
                    }
                });
            }
            function borrarItem(id) {
                bootbox.confirm("Desea eliminar el registro N° " + id + " ?", function (result) {
                    if (result) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('censos') }}/" + id,
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