<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use App\Canino;
use App\User;

class CaninoController extends Controller
{
    public function createCanino(){
        return view('canino.createCanino');
    }


    public function saveCanino(Request $request){
        $validatedData = $this->validate($request, [
            'nombre' => 'required',
            'image' => 'required',
            'raza' => 'required',
            'nacimiento' => 'required',
            'genero' => 'required',
            'agresivo' => 'required',
            'peso' => 'required',
            'tipo_comida' => 'required',
            'extras' => 'required'
        ]);

        $canino = new Canino();
        $user = \Auth::user();
        $canino->user_id = $user->id;
        $canino->nombre = $request->input('nombre');

        $image = $request->file('image');
        if($image){
            $image_path = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_path, \File::get($image));

            $canino->image = $image_path;
        }

        $canino->raza = $request->input('raza');
        $canino->nacimiento = $request->input('nacimiento');
        $canino->genero = $request->input('genero');
        $canino->agresivo = $request->input('agresivo');
        $canino->peso = $request->input('peso');
        $canino->tipo_comida = $request->input('tipo_comida');
        $canino->extras = $request->input('extras');

        $canino->save();

        return redirect()->route('home')->with(array('message' => 'La mascota fue correctamente creado'));

    }

    public function getImageCanino($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 400);
    }

    public function editCanino($canino_id){
        $user = \Auth::user();
        $canino = Canino::findOrFail($canino_id);

        if($user && $canino->user_id == $user->id){
            return view('canino.editCanino', array('canino' => $canino));
        }else{
            return redirect()->route('home');
        }
    }

    public function updateCanino($canino_id, Request $request){
        $validatedData = $this->validate($request, [
            'nombre' => 'required',
            'image' => 'required',
            'raza' => 'required',
            'nacimiento' => 'required',
            'genero' => 'required',
            'agresivo' => 'required',
            'peso' => 'required',
            'tipo_comida' => 'required',
            'extras' => 'required'
        ]);

        $user = \Auth::user();
        $canino = Canino::findOrFail($canino_id);
        $canino->user_id = $user->id;
        $canino->nombre = $request->input('nombre');

        $image = $request->file('image');
        if($image){
            $image_path = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_path, \File::get($image));

            $canino->image = $image_path;
        }

        $canino->raza = $request->input('raza');
        $canino->nacimiento = $request->input('nacimiento');
        $canino->genero = $request->input('genero');
        $canino->agresivo = $request->input('agresivo');
        $canino->peso = $request->input('peso');
        $canino->tipo_comida = $request->input('tipo_comida');
        $canino->extras = $request->input('extras');

        $canino->update();

        return redirect()->route('home')->with(array('message' => 'La mascota fue correctamente actualizado'));

    }
}
