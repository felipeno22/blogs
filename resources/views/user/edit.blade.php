<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edição de Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <p>Editando o nivel de acesso do usuário <strong>{{$user->name}}</strong></p>
                   <p>Nivel de acesso atual <strong>{{$user->role}}</strong></p>

                </div>

                <div class="p-6 text-gray 900">
                    <form action="{{route('user.update', $user->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Campo hidden para o ID do usuário -->
                        <input type="hidden" id="id" value="{{$user->id}}" name="id">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" id="name" value="{{ $user->name }}" maxlength="255" required autofocus name="name" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="email">Email:</label>
                           <input type="email" name="email" id="email" value="{{ $user->email }}" required autofocus  class="form-control" />
                        </div>



                        <label for="role">Selecione um nivel</label></br>
                        <select name="role" id="" required class="py-1 px-8 rounded">
                            <option value="" disabled="disabled">Selecione</option>
                            <option value="user" {{ $user->role=='user' ? "selected"  : ""}} >Usuario Comum</option>
                            <option value="admin"  {{ $user->role=='admin' ? "selected"  : ""}}>Administrador</option>
                            <option value="author" {{ $user->role=='author' ? "selected"  : ""}}>Autor</option>

                        </select>
                        <button type="submit" class="bg-gray-500 text-white rounded p-2 mr-2">
                            <i class="fa-regular fa-floppy-disk"></i> Salvar Alterações</button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
