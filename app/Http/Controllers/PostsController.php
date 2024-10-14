<?php

namespace App\Http\Controllers;

use App\Http\Middleware\middleware;
use Illuminate\Routing\Controller;
use App\Models\Category;
use App\Models\Comments;
use App\Models\Posts;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
    {

// Admin e Author podem acessar todas as rotas, exceto o dashboard
$this->middleware('can:autoriza_admin_author')->except(['dashboard','storeComment','storeComment2']);

// Todos os usuários (admin, author, e user) podem acessar o dashboard
$this->middleware('can:autoriza_todos_users')->only(['dashboard','storeComment','storeComment2']);




    }

     public function dashboard()
     {
         //$posts=new  Posts();

          // Busca  todos posts
        //  $posts = $posts->get();

        // Busca todos os posts e seus comentários
    $posts = Posts::where('status', 'published')->with('comments') ->orderBy('created_at', 'desc')->paginate(5); // 5 posts por página;



    foreach ($posts as $post) {
        $post->user_liked = $post->likes()->where('user_id', Auth::id())->exists();
    }

         // Busca as tags apenas desse usuário
    $tags = Tag::all();

    // Busca as categorias apenas desse usuário
    $categories = Category::all();




         return view('dashboard',['posts'=>$posts, 'tags'=>$tags, 'categories'=>$categories]);
     }

    public function index(Request $request)
    {

        if(Auth::user()->role=='admin'){


        // Busca as tasks apenas desse usuário
        $posts = Posts::query();
        }else{
                 // Obtém o usuário logado
    $userId = Auth::user()->id;
    // Iniciar uma consulta à tabela de categorias desse user logado
    $posts = Posts::where('user_id', $userId);


        }

          // Verificar se há um filtro presente na requisição
if ($request->filled('search')) {
    $filter = $request->input('search');
    // Agrupando as condições de título e descrição para evitar problemas de orWhere
    $posts->where(function ($posts) use ($filter) {
        $posts->where('title', 'LIKE', "%{$filter}%")
          ->orWhere('content', 'LIKE', "%{$filter}%");
    });
}

// Paginação de 10 itens por página, adicionando filtros à URL de paginação
$posts = $posts->paginate(5)->appends($request->query());

        return view('posts.index',[
             'posts'=>$posts
             ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          // Obtém o usuário logado
            $user = Auth::user();

            if($user->role=='admin'){
                $categories = Category::All();
                $tags = Tag::All();
            }else{
                  // Busca as tags apenas desse usuário
                    $tags = $user->tag;

                // Busca as categorias apenas desse usuário
                $categories = $user->category;

            }



        return view('posts.create',['categories'=>$categories,'tags'=>$tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|integer|exists:categories,id',
            'tags' => 'required|array|exists:tags,id',
            'status' => 'required|string',
            'slug' => 'required|string',
        ]);

        $post = new Posts();
        $post->title = $request->title;
        $post->user_id = $request->user_id;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $post->status = $request->status;
        $post->slug = $request->slug;

         // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
         $requestOnly = $request->only(['title','user_id', 'content', 'category_id','status','slug','tags']);

         // Preencher o modelo com os dados filtrados
         $post->fill($requestOnly);

         $post->save();

          // Depois de salvar  sincronize as tags selecionadas
     if ($request->has('tags')) {
         $post->tags()->sync($request->tags);
     }


         return redirect()->route('posts.index')->with('msg','Posts salvo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Posts $posts)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posts $post)
    {


    $selectedTags = $post->tags->pluck('id')->toArray();


        // Busca as tags apenas desse usuário
    //$tags = Tag::all();




         // Obtém o usuário logado
         $user = Auth::user();

         if($user->role=='admin'){
            $categories = Category::All();
            $tags = Tag::All();
        }else{
              // Busca as tags apenas desse usuário
                $tags = $user->tag;

            // Busca as categorias apenas desse usuário
            $categories = $user->category;

        }


        return view('posts.edit',['post' => $post,'categories'=>$categories,'tags'=>$tags,  'selectedTags' => $selectedTags]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Posts $post)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|integer|exists:categories,id',
            'tags' => 'required|array|exists:tags,id',
            'status' => 'required|string',
            'slug' => 'required|string',
        ]);

         //ONLY SO ENVIA PARA ALTERAR SOMENTE ESSES CAMPOS
         Posts::findOrFail($post->id)->update($request->only(['title','user_id', 'content', 'category_id','status','slug','tags']));

         // Sincronizar as tags selecionadas (se houver)
         if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }
        return redirect()->route('posts.index') ;
    }



    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Posts $post)
    {

        if($post->comments()->exists()) {

            return redirect()->route('posts.index')->with('error', 'Não é possível excluir esse post porque possui comentários.');
        }
         // Excluir a tag
     $post->delete();

     return redirect()->route('posts.index')
         ->with('msg', 'post excluído com sucesso.');

    }

    public function confirm_delete_posts(Posts $post){
        return view('posts.confirm_delete_posts',['post'=>$post]);

    }



public function storeComment(Request $request, $postId)
{
    $request->validate([
        'author' => 'required|string',
        'content' => 'required|string',
    ]);



    $comments = new Comments();
    $comments->post_id = $postId;
    $comments->user_id = $request->user_id;
    $comments->content = $request->content;


     // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
     $requestOnly = $request->only(['user_id', 'content']);

     // Preencher o modelo com os dados filtrados
     $comments->fill($requestOnly);

     $comments->save();

    return redirect()->route('dashboard')->with('success', 'Comentário adicionado!');
}

public function storeComment2(Request $request, $postId)
{
    $request->validate([
        'author' => 'required|string',
        'content' => 'required|string',
    ]);



    $comments = new Comments();
    $comments->post_id = $postId;
    $comments->user_id = $request->user_id;
    $comments->content = $request->content;



    // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
    $requestOnly = $request->only(['user_id', 'content']);

    // Preencher o modelo com os dados filtrados
    $comments->fill($requestOnly);

    $comments->save();


    // Retornar o novo comentário como JSON
    return response()->json([
        'comment' => $comments->load('user'), // Carregar o relacionamento com 'user'
        'message' => 'Comentário adicionado com sucesso!'
    ]);
}


public function filterByCategory($category_id)
{
    // Pega os posts que pertencem a essa categoria
    //posts = Posts::where('category_id', $category_id)->get();//versao nao paginada

     // Pega os posts que pertencem a essa categoria com paginação
     $posts = Posts::where('status', 'published')->where('category_id', $category_id)->with('comments')->orderBy('created_at', 'desc')->paginate(5); // 5 posts por página

    $tags = Tag::all();
    $categories = Category::all();

    return view('dashboard',["posts"=>$posts,"categories"=>$categories,"tags"=>$tags]);
}

public function filterByTag($tag_id)
{
    // Pega os posts que pertencem a essa tag através da relação many-to-many
    //$posts = Posts::whereHas('tags', function($query) use ($tag_id) {
      //  $query->where('tag_id', $tag_id);
    //})->get(); // versao nao paginada

    // Pega os posts que pertencem a essa tag com paginação
    $posts = Posts::whereHas('tags', function($query) use ($tag_id) {
        $query->where('tag_id', $tag_id);
    })->where('status', 'published')->with('comments')->orderBy('created_at', 'desc')->paginate(5); // 5 posts por página

    $tags = Tag::all();
    $categories = Category::all();


    return view('dashboard', ["posts"=>$posts,"categories"=>$categories,"tags"=>$tags] );
}

public function filterBySearch(Request $request)
{
    $search = $request->input('search'); // Obtém o valor da busca

    // Pega os posts cujo título ou conteúdo correspondem à busca
   // $posts = Posts::where('title', 'LIKE', '%' . $search . '%')
      //  ->orWhere('content', 'LIKE', '%' . $search . '%')
       // ->get();//versao nao paginada

    // Pega os posts cujo título ou conteúdo correspondem à busca, com paginação
    $posts = Posts::where('status', 'published')->with('comments')->where('title', 'LIKE', '%' . $search . '%')
    ->orWhere('content', 'LIKE', '%' . $search . '%')->orderBy('created_at', 'desc')
    ->paginate(5); // 5 posts por página

    $tags = Tag::all();
    $categories = Category::all();

    return view('dashboard', ["posts" => $posts, "categories" => $categories, "tags" => $tags]);
}



}
