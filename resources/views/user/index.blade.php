<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="bg-white p-5 shadow-lg rounded-lg">
                <h1 class="mb-4">Usuários</h1>

                <!-- Botão de Adicionar Categoria -->
                <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Adicionar Novo User</a>

                <!-- Formulário de Pesquisa -->
                <form action="{{ route('user.index') }}" method="GET" class="mb-4">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Pesquisar categoria...">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </div>
                </form>

                <!-- Mensagens de Feedback -->
                @if(session('msg'))
                    <div class="alert alert-success text-center">{{ session('msg') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <table class='table-auto w-full'>
                    <thead>
                        <tr>
                            <th class='text-center p-4'>Nivel</th>
                            <th class='text-center p-4'>Nome</th>
                            <th class='text-center p-4' >E-mail</th>
                            <th class='text-center p-4'>Data de Cadastro</th>

                            <th class='text-center p-4'>Ações</th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-100">
                            <td class="text-center">
                            @if($user->role=='admin')
                            <i class="fa-sharp fa-solid fa-user-tie"></i>
                            @elseif($user->role=='user')
                            <i class="fa-solid fa-user"></i>
                            @else
                            <i class="fa-sharp-duotone fa-solid fa-user-pen"></i>
                            @endif

                            </td>
                            <td class=" text-center p-2">
                                <div style="position: relative; display: inline-block; text-align: center;">
                                    <!-- Imagem do perfil -->
                                    <img style="width: 50px; height: 50px; position: absolute; top: 0; left: 50%; transform: translateX(-50%);"
                                        src="{{ $user->profile_picture != null ? asset('storage/' . $user->profile_picture) : asset('storage/profile_pictures/perfil.jpg' )  }}"
                                        class="rounded-full"
                                        alt="Foto de perfil"/>

                                    <!-- Nome do usuário -->
                                    <p style="margin-top: 60px;">{{ $user->name}}</p>
                                </div>



                            </td>
                            <td class=" text-center p-2" >{{ $user->email}}</td>
                            <td class=" text-center p-2" >{{ $user->created_at}}</td>


                            <td class=" text-center p-2">
                                <a href="{{route('user.edit', $user)}}">editar</a>
                            </td>

                        </tr>

                        @endforeach
                    </tbody>
                </table>

                <div class="p-6 bg-gray-300 text-gray-900">
                    <div class=" bg-gray-188 rounded-lg">
                        <p><strong>Legenda</strong></p>
                        <p><i class="fa-sharp fa-solid fa-user-tie"></i><strong> - Usuário Administrador</strong></p>
                        <p><i class="fa-solid fa-user"></i><strong> - Usuário Comum</strong></p>
                        <p><i class="fa-sharp-duotone fa-solid fa-user-pen"></i><strong> - Autor</strong></p>

                    </div>
            </div>

            <div class="p-6 bg-gray-300 text-gray-900">
                <div class="p-3 bg-gray-188 rounded-lg">
                     <!-- Links de paginação -->
                {{ $users->links() }}

                </div>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>
