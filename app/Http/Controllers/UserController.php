<?php

namespace App\Http\Controllers;

use App\Http\Middleware\middleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
   public function __construct()
    {
        $this->middleware('can:autoriza_admin')->only('index');
        $this->middleware('can:autoriza_admin')->only('editar');
        $this->middleware('can:autoriza_admin')->only('update');
    }


    public function index(Request $request){

    // Busca as tasks apenas desse usuário
        $users = User::query();

          // Verificar se há um filtro presente na requisição
if ($request->filled('search')) {
    $filter = $request->input('search');
    $users->where('name', 'like', '%' . $filter . '%')
    ->orWhere('email', 'like', '%' . $filter . '%');
}

// Paginação de 10 itens por página, adicionando filtros à URL de paginação
$users = $users->paginate(5)->appends($request->query());


        return view('user.index',[
            //'users'=> DB::table('users')->orderBy('name')->paginate('5'),
            'users'=> $users
        ]);

    }

    public function create(){
        return view('user.create');

    }

    public function store(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()]

        ]);



        $user=new   User();

        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= Hash::make($request->password);


       /* $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);*/

         // Filtrar os dados da requisição para incluir apenas os campos que você deseja atualizar
         $requestOnly = $request->only(['name','email','password']);

         // Preencher o modelo com os dados filtrados
         $user->fill($requestOnly);

         $user->save();

         return redirect()->route('user.index')->with('msg','Usuário salvo');




    }

    public function editar(User $user){
        return view('user.edit',[
            'user'=> User::findOrFail($user->id)
        ]);

    }

    public function update(Request $request){

// Validação dos campos
    $request->validate([
    'role' => 'required|string',
    'name' => 'required|string',
    'email' => 'required|string',
    'id' => 'required|integer']);

       // dd($resquest);
       // die();
         User::findOrFail($request->id)->update($request->only(['id','role','name','email']));
         return redirect()->route('user.index');
    }
}
