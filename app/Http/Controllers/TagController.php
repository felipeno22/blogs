<?php

namespace App\Http\Controllers;

use App\Http\Middleware\middleware;
use Illuminate\Routing\Controller;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{


    public function __construct()
    {

// Admin e Author podem acessar todas as rotas, exceto o dashboard
$this->middleware('can:autoriza_admin_author');





    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if(Auth::user()->role=="admin"){

         $tags = Tag::query();
       }else{

              // Obtém o usuário logado
    $userId = Auth::user()->id;
    // Iniciar uma consulta à tabela de categorias desse user logado
    $tags = Tag::where('user_id', $userId);


       }

         // Verificar se há um filtro presente na requisição
if ($request->filled('search')) {
    $filter = $request->input('search');
    $tags->where('name', 'like', '%' . $filter . '%');
}

// Paginação de 10 itens por página, adicionando filtros à URL de paginação
$tags = $tags->paginate(5)->appends($request->query());

        return view('tag.index',[
             'tags'=>$tags
             ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Validação dos campos
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:100',
                'user_id' => 'required|string',
            ]);


            $tag=new   Tag();

            $tag->name= $request->name;
            $tag->slug= $request->slug;
            $tag->user_id= $request->user_id;

            // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
                $requestOnly = $request->only(['name','slug','user_id']);

                // Preencher o modelo com os dados filtrados
                $tag->fill($requestOnly);

                $tag->save();

                return redirect()->route('tag.index')->with('msg','Tag salva');


    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('tag.edit',['tag'=> $tag]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
          // Validação dos campos
          $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100',
            'user_id' => 'required|string',
        ]);

        //ONLY SO ENVIA PARA ALTERAR SOMENTE ESSES CAMPOS
            Tag::findOrFail($tag->id)->update($request->only(['name','slug','user_id']));
        return redirect()->route('tag.index');
    }


 public function confirm_delete_tag(Tag $tag){
    return view('tag.confirm_delete_tag',['tag'=>$tag]);

}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {

        if($tag->posts()->exists()) {

            return redirect()->route('tag.index')->with('error', 'Não é possível excluir a tag porque ela está associada a um post.');
        }
         // Excluir a tag
     $tag->delete();

     return redirect()->route('tag.index')
         ->with('msg', 'tag excluída com sucesso.');

    }
}
