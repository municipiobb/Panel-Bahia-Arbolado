@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Listado de censos</div>

        <div class="panel-body">
          @include('flash::message')

          <p>Filtros</p>
          <form action="" method="get" accept-charset="utf-8">

            <div class="col-md-2">
              <label>Especie</label>
              <select id="especies" class="especies" name="especie" style="width:100%">
                <option value="0">Especie</option>
                @foreach($especies as $especie)
                <option value="{{$especie->id}}" {{ request()->especie == $especie->id ? 'selected': ''}}>{{ $especie->nombre }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label>Estado</label>
              <select id="estado" class="estado" name="estado" style="width:100%">
                <option value="">Estado</option>
                <option value="Bueno" {{ request()->estado == 'Bueno' ? 'selected': ''}}>Bueno</option>
                <option value="Regular" {{ request()->estado == 'Redular' ? 'selected': ''}}>Regular</option>
                <option value="Malo" {{ request()->estado == 'Malo' ? 'selected': ''}}>Malo</option>
              </select>
            </div>
            <div class="col-md-2">
              <label>Tamaño arbol</label>
              <select id="tamanio" class="tamanio" name="tamanio" style="width:100%">
                <option value="">Tamaño</option>
                <option value="Chico" {{ request()->tamanio == 'Chico' ? 'selected': ''}}>Chico</option>
                <option value="Mediano" {{ request()->tamanio == 'Mediano' ? 'selected': ''}}>Mediano</option>
                <option value="Grande" {{ request()->tamanio == 'Grande' ? 'selected': ''}}>Grande</option>
              </select>
            </div>

            <div class="col-md-2">
              <label>Diametro Tronco</label>
              <select id="diametro" class="diametro" name="diametro" style="width:100%">
                <option value="">Diametro</option>
                <option value="-30 cm" {{ request()->diametro == '-30 cm' ? 'selected': ''}}>-30 cm</option>
                <option value="+50 cm" {{ request()->diametro == '+50 cm' ? 'selected': ''}}>+50 cm</option>
                <option value="30-50 cm" {{ request()->diametro == '30-50 cm' ? 'selected': ''}}>30-50 cm</option>
              </select>
            </div>

            <div class="col-md-2">
              <label>Ancho Vereda</label>
              <select id="ancho_vereda" class="ancho_vereda" name="ancho_vereda" style="width:100%">
                <option value="">Ancho</option>
                <option value="-1.5 mts" {{ request()->ancho_vereda == '-1.5 mts' ? 'selected': ''}}>-1.5 mts</option>
                <option value="+3.5 mts" {{ request()->ancho_vereda == '+3.5 mts' ? 'selected': ''}}>+3.5 mts</option>
                <option value="1.5-3.5 mts" {{ request()->ancho_vereda == '1.5-3.5 mts' ? 'selected': ''}}>1.5-3.5 mts</option>
              </select>
            </div>

            <div class="col-md-2">
              <label>Tipo Vereda</label>
              <select id="tipo_vereda" class="tipo_vereda" name="tipo_vereda" style="width:100%">
                <option value="">Tipo</option>
                <option value="Baldosa" {{ request()->tipo_vereda == 'Baldosa' ? 'selected': ''}}>Baldosa</option>
                <option value="Tierra" {{ request()->tipo_vereda == 'Tierra' ? 'selected': ''}}>Tierra</option>
                <option value="Tierra y cesped" {{ request()->tipo_vereda == 'Tierra y cesped' ? 'selected': ''}}>Tierra y cesped</option>

                <option value="Tierra, cesped y baldosa" {{ request()->tipo_vereda == 'Tierra, cesped y baldosa' ? 'selected': ''}}>Tierra, cesped y baldosa</option>
              </select>
            </div>

            <button class="btn btn-sm btn-primary" style="margin-top: 25px;">Buscar</button>
            <a class="btn btn-sm btn-default" style="margin-top: 25px;" href="{{url('/')}}">Limpiar</a>
          </form>


          <table class="table">
            <caption style="text-align:right">
              <p><i class="fa fa-pencil" aria-hidden="true"></i> (Editar) | <i class="fa fa-eye" aria-hidden="true"></i> (Ver) | <i class="fa fa-check" aria-hidden="true"></i> (Aprobar)</p>
            </caption>
            <thead>
              <tr>
                <th>#</th>
                <th>Especie</th>
                <th>Estado</th>
                <th>Tamaño Arbol</th>
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
                  <a href="{{ action('CensosController@edit', $censo->id) }}" title="Editar" style="font-size: 18px; color: #00c2ff;" title="">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                  </a>
                  <a href="{{ action('CensosController@show', $censo->id) }}" title="Ver" style="font-size: 18px; color: #ff9b00;" title="">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                  </a>
                  <a id="borrar-{{$censo->id}}" href="#" style="font-size: 18px; color: #ef0000;" title="Borrar" onclick="borrarItem({{ $censo->id }})">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                  </a>
                  @if(!$censo->status)
                  <a id="aprobar-{{$censo->id}}" href="#" style="font-size: 18px; color: green;" title="Aprobar" onclick="aprobarItem({{ $censo->id }})">
                    <i class="fa fa-check" aria-hidden="true"></i>
                  </a>
                  @endif
                </td>
                <!-- <td><span style="color:blue">Aprobar</span></td> -->
              </tr>
              @endforeach
            </tbody>
          </table>

          {{ $censos->appends(['especie' => ''])->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css" media="screen">
  .censo-bueno{
    color: green;
  }
  .censo-regular{
    color: orange;
  }
  .censo-malo{
    color: red;
  }
</style>
<script type="text/javascript" charset="utf-8">

  function aprobarItem(id) {

    bootbox.confirm("Desea aprobar el registro N° " + id + " ?", function(result) {
      if(result){
        $.ajax({
          type: "PUT",
          url: "{{ url('censos') }}/" + id + "/aprobar",
          success: success,
          data: { _token: window.Laravel.csrfToken}
        });

        function success(res){
                //$('#row-'+id).remove();
                $('#aprobar-'+id).remove();
              }
            }
          });         
  }
  function borrarItem(id) {

    bootbox.confirm("Desea eliminar el registro N° " + id + " ?", function(result) {
      if(result){
        $.ajax({
          type: "DELETE",
          url: "{{ url('censos') }}/" + id,
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

<script type="text/javascript">
  $(document).ready(function() {
    $("#especies").select2();
    $("#estado").select2();
    $("#tamanio").select2();
    $("#diametro").select2();
    $("#ancho_vereda").select2();
    $("#tipo_vereda").select2();
  });
</script>
@endsection
