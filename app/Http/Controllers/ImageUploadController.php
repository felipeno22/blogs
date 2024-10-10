<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\middleware;
use Illuminate\Routing\Controller;
class ImageUploadController extends Controller
{

    public function __construct()
    {

// Admin e Author podem acessar todas as rotas, exceto o dashboard
$this->middleware('can:autoriza_admin_author');

    }
    public function upload(Request $request)
    {
        // Validação: verifica se o arquivo está presente e se é uma imagem válida
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            //$image->move(public_path('uploads'), $imageName);

            //$url = asset('uploads/' . $imageName);

              // Salvar a imagem em 'storage/app/public/uploads'
        $image->storeAs('public/uploads', $imageName);

        // Gerar a URL correta, apontando para 'public/storage/uploads'
        $url = asset('storage/uploads/' . $imageName);

            // O CKEditor espera um retorno JSON com a URL da imagem
            return response()->json([
                'uploaded'=>true,
                'url' => $url
            ]);
        }

        return response()->json(['uploaded'=>false,"error"=>['message' => 'Falha no upload']], 400);
    }


}
