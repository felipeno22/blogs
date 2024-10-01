<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categorias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="bg-white p-5 shadow-lg rounded-lg">

                <!-- Mensagem de sucesso -->
                @if(session('msg'))
                    <div class="alert alert-success text-center">{{ session('msg') }}</div>
                @endif

                <!-- Formulário de Edição de Categorias -->
                <form action='{{ route("category.update", $category->id )}}' name="cadcategories" method="post">
                    @csrf
                    @method('PATCH')
                    <fieldset class="mb-4">
                        <legend>Preencha todos os campos</legend>

                        <!-- Campo hidden para o ID do usuário -->
                        <input type="hidden" id="user_id" value="{{ Auth::user()->id }}" name="user_id">

                        <!-- Campo Nome da Categoria -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" id="name" value="{{ $category->name }}" maxlength="255" required autofocus name="name" class="form-control" placeholder="Digite o novo nome da categoria">
                        </div>


                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" value="{{ $category->slug }}" maxlength="100" required autofocus name="slug" class="form-control" >
                        </div>

                    </fieldset>

                    <!-- Botão de Alterar -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
