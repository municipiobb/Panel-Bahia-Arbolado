@extends('layouts.app')

@section('styles')
    <link href="{{ asset('darkroomjs/build/darkroom.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Editar Calle</h4>
                    </div>

                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <br><br>
                        {!! Form::model($imagen, ['action' => ['ImagenesController@update', $imagen->id], 'method' => 'PUT', 'files' => true]) !!}
                        <img id="target" src="{{ asset($imagen->url) }}" alt="">
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('darkroomjs/vendor/fabric.js') }}"></script>
    <script src="{{ url('darkroomjs/build/darkroom.js') }}"></script>

    <script>
        var dkrm = new Darkroom('#target', {
            // Size options
            minWidth: 100,
            minHeight: 100,
            maxWidth: 600,
            maxHeight: 500,
            ratio: 4 / 3,
            backgroundColor: '#000',

            // Plugins options
            plugins: {
                //save: false,
                crop: {
                    quickCropKey: 67, //key "c"
                    //minHeight: 50,
                    //minWidth: 50,
                    //ratio: 4/3
                },
                save: {
                    callback: function() {
                        this.darkroom.selfDestroy(); // Cleanup
                        var image = dkrm.canvas.toDataURL();
                        console.log(image);
                    }
                }
            },

            // Post initialize script
            initialize: function () {
                var cropPlugin = this.plugins['crop'];
                // cropPlugin.selectZone(170, 25, 300, 300);
                cropPlugin.requireFocus();
            }
        });
    </script>
@endsection