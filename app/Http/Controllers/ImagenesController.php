<?php

namespace App\Http\Controllers;

use Image;
use App\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagenesController extends Controller
{
    public function index()
    {
        $imagenes = Imagen::orderBy('id', 'desc')->paginate(30);

        return view('imagenes.index', compact('imagenes'));
    }

    public function store(Request $request)
    {
        return response()->json([
            'success' => 1
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(!auth()->user()->isAdmin()) {
            flash('No tiene permiso para actualizar el registro.', 'warning');
            return redirect()->route('index');
        }

        $imagen = Imagen::findOrFail($id);

        $url = $imagen->url;

        if ( $request->hasFile('imagen') ) {
            $image = $request->file('imagen');
            $filename = time() . '.jpg';
            $relative_path = 'uploads' . DIRECTORY_SEPARATOR . $filename;
            $path = public_path($relative_path);

            /** @var \Intervention\Image\Image $resized */
            $resized = Image::make($image->getRealPath())->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resized->save($path);

            Storage::disk('public')->delete($url);

            $imagen->update([
                'url' => $relative_path
            ]);

            return response()->json([
                'success' => 1,
                'imagen' => asset($relative_path)
            ]);
        } else {
            return response()->json([
                'success' => 0
            ]);
        }
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        /** @var Imagen $imagen */
        $imagen = Imagen::findOrFail($id);

        return view('imagenes.edit2', compact('imagen'));
    }
    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if(!auth()->user()->isAdmin()) {
            flash('No tiene permiso para eliminar el registro.', 'warning');
            return redirect()->route('index');
        }

        /** @var Imagen $imagen */
        $imagen = Imagen::findOrFail($id);

        Storage::disk('public')->delete($imagen->url);

        if ( $imagen->delete() ) {
            return response()->json(
                [
                    'success' => 1,
                    'flash' => 'Imagen Eliminada.'
                ]
            );
        }

        return response()->json(
            [
                'success' => 0,
                'flash' => 'Ah ocurrido un error.'
            ]
        );
    }
}
