<!-- CSS para garantir o estilo persistente do ckeditor5 -->
<style>
    .ck-editor__editable {
        min-height: 200px;
        height: 200px;
        border: 1px solid #ddd;
        padding: 10px;
        background-color: #f9f9f9;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
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
      <form action="{{ route('posts.store') }}" name="cadposts" method="post" onsubmit="acao()">
        @csrf
        <fieldset class="mb-4">
            <legend>Preencha para seu post</legend>

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>


       <!-- <label>Conteúdo</label>
        <textarea name="content" id="content"  hidden></textarea>
        <div id="editor" class="form-group">

        </div>-->

        <textarea name="content" id="content" >
            Escreva sua postagem aqui...
        </textarea>



        <div class="form-group">
            <label for="category_id">Categoria</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Selecione uma Categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <select name="tags[]" id="tags" class="form-control" multiple>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>



        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="draft">Rascunho</option>
                <option value="published">Publicado</option>
            </select>
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" maxlength="100" value="{{ old('slug') }}" required>
        </div>

        <input type="text" id="user_id" value="{{ Auth::user()->id }}" name="user_id" hidden />

        <button type="submit" class="btn btn-success">Salvar Post</button>
    </form>

</div>
</div>
</div>
</x-app-layout>
<script>
function acao(){

    // Função para salvar o conteúdo no textarea escondido
    document.querySelector('form').addEventListener('submit', () => {
        document.querySelector('#content').value = editor.getData();
    });
}


    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote',
                'insertTable', 'mediaEmbed', 'undo', 'redo', 'imageUpload'
            ],
            ckfinder: {
                // Configurações para uploads de imagem (opcional)
                uploadUrl:'/upload-image',
            },
            mediaEmbed: {
                previewsInData: true
            }
        }).then(editor => {

             // Definindo estilos iniciais
    const editableElement = editor.ui.view.editable.element;
    applyCustomStyles(editableElement);

    // Evento para aplicar os estilos ao focar no editor
    editor.editing.view.document.on('focus', () => {
        applyCustomStyles(editableElement);
    });

    // Função para aplicar estilos personalizados
    function applyCustomStyles(element) {
        element.style.minHeight = '200px';  // Define a altura mínima
        element.style.height = '200px';  // Define a altura
        element.style.border = '1px solid #ddd'; // Define a borda
        element.style.padding = '10px'; // Define o padding
        element.style.backgroundColor = '#f9f9f9'; // Define cor de fundo

    }


}) .catch(error => {
            console.error(error);
        });
</script>
