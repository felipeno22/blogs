<?php

namespace App\Http\Controllers;

use App\Http\Middleware\middleware;
use Illuminate\Routing\Controller;
use App\Models\Comments;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
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

     if(Auth::user()->role=='admin'){


         // Busca as tasks apenas desse usuário
         $comments = Comments::query();

     }else{
             // Obtém o usuário logado
    $userId = Auth::user()->id;
    // Iniciar uma consulta à tabela de categorias desse user logado
    $comments = Comments::where('user_id', $userId);

     }

     if ($request->filled('search')) {
        $filter = $request->input('search');
        $comments->where(function ($comments) use ($filter) {
            $comments->where('content', 'LIKE', "%{$filter}%") // Busca no conteúdo do comentário
                  ->orWhereHas('user', function ($user) use ($filter) { // Busca pelo nome do usuário
                      $user->where('name', 'LIKE', "%{$filter}%");
                  })->orWhereHas('posts', function ($posts) use ($filter) { // Busca pelo título do post
                      $posts->where('title', 'LIKE', "%{$filter}%");
                  });
        });
    }

 // Paginação de 5 itens por página, mantendo os filtros na URL
 $comments = $comments->paginate(5)->appends($request->query());


    return view('comments.index',[
        'comments'=>$comments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    // Busca todos posts
    $posts = Posts::all();

        return view('comments.create',['posts'=>$posts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validação dos campos
         $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer',
        ]);


        $comment=new Comments();

        $comment->content= $request->content;
        $comment->user_id= $request->user_id;
        $comment->post_id= $request->post_id;

        // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
            $requestOnly = $request->only(['content','user_id','post_id']);

            // Preencher o modelo com os dados filtrados
            $comment->fill($requestOnly);

            $comment->save();

            return redirect()->route('comments.index')->with('msg','Comentario salvo');

    }

    /**
     * Display the specified resource.
     */
    public function show(Comments $comments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comments $comment)
    {
        // Busca todos posts
    $posts = Posts::all();


        return view('comments.edit',['comment'=> $comment,'posts'=>$posts]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comments $comment)
    {
        // Validação dos campos
        $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer',


        ]);

        //ONLY SO ENVIA PARA ALTERAR SOMENTE ESSES CAMPOS
        Comments::findOrFail($comment->id)->update($request->only(['content','user_id','post_id']));
        return redirect()->route('comments.index');
    }


 public function confirm_delete_comment(Comments $comment){
    return view('comments.confirm_delete_comment',['comment'=>$comment]);

}






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comments $comment)
    {

  // Excluir um commentario
     $comment->delete();

     return redirect()->route('comments.index')
         ->with('msg', 'Comentário excluído com sucesso.');

    }
}
