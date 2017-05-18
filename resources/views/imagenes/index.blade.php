@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>Imágenes</h4></div>

                    <div class="panel-body">
                        @include('flash::message')

                        @foreach($imagenes as $imagen)
                            <div id="box-{{ $imagen->id }}" class="col-md-4">
                                <div class="img-box">
                                    @if(auth()->user()->isAdmin())
                                        <div class="controls" style="display: none;">
                                            <a href="{{ action('ImagenesController@edit', $imagen->id) }}"
                                               style="font-size: 18px; color: #00c2ff;" title="">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                            <a href="#" style="font-size: 18px; color: red;" title=""
                                               onclick="deleteItem({{ $imagen->id }})">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    @endif
                                    <img src="{{ $imagen->url }}" alt=""
                                         style="width: 100%;border: 1px solid silver;padding: 5px;border-radius: 3px;margin-bottom: 10px;">
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-12">
                            {{ $imagenes->links() }}
                        </div>
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

        .img-box {
            position: relative;
        }

        .controls {
            position: absolute;
            top: 6px;
            right: 6px;
            padding: 5px 10px;
            background: #fffcfcb3;
        }

        .img-box:hover .controls {
            display: block !important;
        }
    </style>
    @if(auth()->user()->isAdmin())
        <script type="text/javascript" charset="utf-8">
            function deleteItem(id) {

                bootbox.confirm("Desea eliminar el registro N° " + id + " ?", function (result) {
                    if (result) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('imagenes') }}/" + id,
                            success: function () {
                                $('#box-' + id).remove();
                            },
                            data: {_token: window.Laravel.csrfToken}
                        });


                    }
                });
            }
        </script>
    @endif
@endsection