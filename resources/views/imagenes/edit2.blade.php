@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/cropper.min.css') }}" rel="stylesheet">

    <style>
        img {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Editar Foto</h4>
                    </div>

                    <div class="panel-body">
                        <br><br>
                        <div class="img-container" style="max-height: 400px;">
                            <img id="image" src="{{ asset($imagen->url) }}" alt="">
                        </div>

                        <div class="row" id="actions" style="margin-top: 15px;">
                            <div class="col-md-12 docs-buttons">
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-primary"
                                            data-method="setDragMode"
                                            data-option="move"
                                            title="Move">
            <span class="docs-tooltip"
                  data-toggle="tooltip" title=""
                  data-original-title="cropper.setDragMode(&quot;move&quot;)">
              <span class="fa fa-arrows"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="setDragMode"
                                            data-option="crop"
                                            title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" title=""
                  data-original-title="cropper.setDragMode(&quot;crop&quot;)">
              <span class="fa fa-crop"></span>
            </span>
                                    </button>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1"
                                            title="Zoom In">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(0.1)"
                  aria-describedby="tooltip838762">
              <span class="fa fa-search-plus"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1"
                                            title="Zoom Out">
            <span class="docs-tooltip"
                  data-toggle="tooltip"
                  title=""
                  data-original-title="cropper.zoom(-0.1)"><span class="fa fa-search-minus"></span>
            </span>
                                    </button>
                                </div>


                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="move" data-option="-10"
                                            data-second-option="0" title="Move Left">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.move(-10, 0)">
              <span class="fa fa-arrow-left"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="move" data-option="10"
                                            data-second-option="0" title="Move Right">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.move(10, 0)">
              <span class="fa fa-arrow-right"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0"
                                            data-second-option="-10" title="Move Up">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.move(0, -10)"
                  aria-describedby="tooltip663908">
              <span class="fa fa-arrow-up"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0"
                                            data-second-option="10" title="Move Down">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.move(0, 10)">
              <span class="fa fa-arrow-down"></span>
            </span>
                                    </button>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45"
                                            title="Rotate Left">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.rotate(-45)">
              <span class="fa fa-rotate-left"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45"
                                            title="Rotate Right">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.rotate(45)">
              <span class="fa fa-rotate-right"></span>
            </span>
                                    </button>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.crop()">
              <span class="fa fa-check"></span>
            </span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.clear()">
              <span class="fa fa-remove"></span>
            </span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="getCroppedCanvas">
            <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.getCroppedCanvas()">
              Guardar
            </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .btn-primary {
            color: #fff;
            background-color: #3097D1;
            border-color: #2a88bd;
        }
    </style>
@endsection

@section('scripts')
    <script>
        var _BaseURL = '{{ action('ImagenesController@update', ['id' => $imagen->id]) }}'
    </script>
    <script src="{{ url('js/cropper.min.js') }}"></script>
    <script src="{{ url('js/cropper_main.js') }}"></script>

    <script>
        /*var image = document.getElementById('image');
         var cropper = new Cropper(image, {
         aspectRatio: 16 / 9,
         crop: function (e) {
         console.log(e.detail.x);
         console.log(e.detail.y);
         console.log(e.detail.width);
         console.log(e.detail.height);
         console.log(e.detail.rotate);
         console.log(e.detail.scaleX);
         console.log(e.detail.scaleY);
         }
         });*/
    </script>
@endsection