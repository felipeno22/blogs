<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastro de  Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

                <div class="p-6 text-gray 900">
                    <form action="{{route('user.store')}}" method="post" enctype="multipart/form-data">
                        @csrf


                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" id="name" value="{{ old('name') }}" maxlength="255" required autofocus name="name" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="email">Email:</label>
                           <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus  class="form-control" />
                        </div>

                          <!-- Password -->
                        <div class="mt-4">

                            <label for="password">Password:</label>
                           <input type="password" name="password" id="password" value="" required autofocus  class="form-control" />
                           <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        </div>


                         <!-- Confirm Password -->
                         <div class="mt-4">
                            <label for="password_confirmation">Confirme Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" value="" required autofocus  class="form-control" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                        </div>

                        <div class="mt-4">
                        <label for="role">Selecione um nivel</label></br>
                        <select name="role" id="" required class="py-1 px-8 rounded">
                            <option value="" disabled="disabled">Selecione</option>
                            <option value="user" >Usuario Comum</option>
                            <option value="admin">Administrador</option>
                            <option value="author">Autor</option>

                        </select>

                        <button type="submit" class="bg-gray-500 text-white rounded p-2 mr-2">
                            <i class="fa-regular fa-floppy-disk"></i> Salvar</button>

                    </div>




                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
