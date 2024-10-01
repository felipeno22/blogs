<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Middleware\middleware;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
             //$category=new Category();
            //$categories = $category->get();


            $categories = Category::query();
        }else{
            // Obtém o usuário logado
    $userId = Auth::user()->id;
    // Iniciar uma consulta à tabela de categorias desse user logado
    $categories = Category::where('user_id', $userId);


        }

        // Verificar se há um filtro presente na requisição
if ($request->filled('search')) {
    $filter = $request->input('search');
    $categories->where('name', 'like', '%' . $filter . '%');
}

// Paginação de 10 itens por página, adicionando filtros à URL de paginação
$categories = $categories->paginate(5)->appends($request->query());


        return view('category.index',[
             'categories'=>$categories
             ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
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


        $category=new Category();

        $category->name= $request->name;
        $category->slug= $request->slug;
        $category->user_id= $request->user_id;

        // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
            $requestOnly = $request->only(['name','slug','user_id']);

            // Preencher o modelo com os dados filtrados
            $category->fill($requestOnly);

            $category->save();

            return redirect()->route('category.index')->with('msg','Categoria salva');

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {


        return view('category.edit',['category'=> $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:100',
            'user_id' => 'required|string',
        ]);

        //ONLY SO ENVIA PARA ALTERAR SOMENTE ESSES CAMPOS
        Category::findOrFail($category->id)->update($request->only(['name','slug','user_id']));
        return redirect()->route('category.index');
    }


 public function confirm_delete_category(Category $category){
    return view('category.confirm_delete_category',['category'=>$category]);

}






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->posts()->exists()) {

            return redirect()->route('category.index')->with('error', 'Não é possível excluir a categoria porque ela está associada a um post.');
        }
         // Excluir a tag
     $category->delete();

     return redirect()->route('category.index')
         ->with('msg', 'Categoria excluída com sucesso.');

    }



    public function filter(Request $request) {

        // Validação dos campos
      $request->validate([
          'search' => 'nullable|string|max:255',
      ]);



      $search = $request->input('search'); // pega  title ou  descricao da task

      $userId =  Auth::user()->id;
      $categories =Category::query();

      if(Auth::user()->role=="admin"){
        //pega todas categorias



      }else{

      // Filtrar todas as categorias do usuario logado pelo id
      $categories->where('user_id', $userId);

      }



      if ($search) {

          // Buscando as tarefas que correspondem ao título ou descrição
          $categories->where(function ($query) use ($search) {
              $query->where('name', 'LIKE', "%{$search}%");
          });



      }


      $categories = $categories->paginate(5);



      return view('categories.index', ['categories'=>$categories]);
    }

}
