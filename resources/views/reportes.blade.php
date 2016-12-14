@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Listado de censos</div>

                    <div class="panel-body">
                        <table class="table">
                            @foreach($reportes as $reporte)
                                <tr>
                                    <td>#ID</td>
                                    <td>{{ $reporte->id }}</td>
                                </tr>
                                @foreach(json_decode($reporte->error) as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach

                            @endforeach
                        </table>
                        {{ $reportes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
