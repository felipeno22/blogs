<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="bg-white p-5 shadow-lg rounded-lg">

                <!-- Mensagem de sucesso -->
                @if(session('msg'))
                    <div class="alert alert-success text-center">{{ session('msg') }}</div>
                @endif

      <!-- Formulário de Cadastro de Tags -->
      <form action="{{ route('posts.update', $post->id) }}" name="cadposts" method="post">
        @csrf
        @method('PATCH')
        <fieldset class="mb-4">
            <legend>Preencha para seu post</legend>

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}" required>
        </div>

        <div class="form-group">
            <label for="content">Conteúdo</label>
            <textarea name="content" id="content" rows="6" maxlength="1000" class="form-control" required>
                {!!$post->content!!}
            </textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Categoria</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Selecione uma Categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}  >
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <select name="tags[]" id="tags" class="form-control" multiple>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}"  {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                        {{ $tag->name }}</option>
                @endforeach
            </select>
        </div>





        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Rascunho</option>
                <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Publicado</option>
            </select>
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" maxlength="100" value="{{ $post->slug }}" required>
        </div>

        <input type="text" id="user_id" value="{{ Auth::user()->id }}" name="user_id" hidden />

        <button type="submit" class="btn btn-success">Salvar Post</button>
    </form>

</div>
</div>
</div>
</x-app-layout>
