<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validação: verifica se o arquivo está presente e se é uma imagem válida
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);

            $url = asset('uploads/' . $imageName);


            // O CKEditor espera um retorno JSON com a URL da imagem
            return response()->json([
                'uploaded'=>true,
                'url' => $url
            ]);
        }

        return response()->json(['uploaded'=>false,"error"=>['message' => 'Falha no upload']], 400);
    }


}
