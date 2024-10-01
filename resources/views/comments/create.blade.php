<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(' Comentários') }}
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
                <form action='{{ route("comments.store")}}' name="cadcomments" method="post">
                    @csrf
                    <fieldset class="mb-4">
                        <legend>Preencha todos os campos</legend>

                        <!-- Campo hidden para o ID do usuário -->
                        <input type="hidden" id="user_id" value="{{ Auth::user()->id }}" name="user_id">

                        <div class="mb-3">
                            <label for="author" class="form-label">Autor</label>
                            <input type="text" id="author" value="{{ Auth::user()->name }}" maxlength="100" required autofocus name="author" class="form-control" >
                        </div>



                        <!-- Campo contéudo  do Comentário-->
                        <div class="mb-3">
                            <label for="content" class="form-label">Contéudo</label>
                            <input type="text" id="content"  maxlength="255" required autofocus name="content" class="form-control" placeholder="Digite o comentário">
                        </div>


                        <div class="form-group">
                            <label for="post_id">Posts</label>
                            <select name="post_id" id="post_id" class="form-control" required>
                                <option value="">Selecione um Post</option>
                                @foreach ($posts as $post)
                                    <option value="{{ $post->id }}" >
                                        {{ $post->title }}</option>
                                @endforeach
                            </select>
                        </div>




                    </fieldset>

                    <!-- Botões de ação -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <button type="reset" class="btn btn-danger">Limpar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
