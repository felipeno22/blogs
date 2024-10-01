<x-app-layout>
    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <!-- Container Principal -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:flex lg:space-x-6 overflow-hidden shadow-sm sm:rounded-lg">



                <!-- Seção Lateral (Sidebar) para Categorias ou Tags -->
                <div class="lg:w-1/4 w-full p-6 bg-white dark:bg-gray-900">
                    <h3 class="text-lg font-bold mb-4">Categorias</h3>
                    <div class="flex flex-wrap">
                        <!-- Exibe categorias do banco -->
                        @foreach ($categories as $category)
                            <a href="{{ route('posts.category', $category->id) }}" class="bg-gray-500 text-white py-1 px-3 rounded-full mr-2 mb-2 hover:bg-gray-700 transition-colors duration-200">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <h3 class="text-lg font-bold mt-6 mb-4">Tags</h3>
                    <div class="flex flex-wrap">
                        <!-- Exibe tags do banco -->
                        @foreach ($tags as $tag)
                            <a href="{{ route('posts.tag', $tag->id) }}" class="bg-gray-500 text-white py-1 px-3 rounded-full mr-2 mb-2 hover:bg-gray-700 transition-colors duration-200">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Formulário de busca de posts -->
                    <div class="flex flex-wrap mt-6">
                        <label for="search">Encontre um post:</label>
                        <form action="{{ route('posts.search', '' ) }}" method="GET" class="d-flex mb-5">
                            <input class="form-control me-2" type="search" name="search" value="{{ request('search') }}" aria-label="Search" id="search">
                            <button class="bg-gray-500 text-white btn btn-outline-success" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>


                <!-- Seção de Listagem de Posts -->
                <div class="lg:w-3/4 w-full p-6 text-gray-900 bg-white">
                    <div class="container">



                        <div class="titlebar flex justify-between items-center mb-6">
                            <h1 class="text-3xl font-bold"><strong>Meus Posts</strong></h1>

                            @if (isset($search))
                            <label><strong>Resultados da busca por: posts "{{ $search }}"</strong></label>
                        @endif

                        @if (isset($search_tag))
                            <label><strong>Resultados da busca por: tag"{{ $search_tag }}"</strong></label>
                        @endif

                        @if (isset($search_cate))
                            <label><strong>Resultados da busca por : categoria "{{ $search_cate }}"</strong></label>
                        @endif

                        </div>

                        <hr class="mb-6"> <!-- Linha de separação -->

                        <!-- Exibe a lista de posts -->
                        @if (count($posts) > 0)
                            @foreach ($posts as $post)
                                <div class="mb-8">
                                    <br><br>
                                    <div class="flex items-center">
                                        <div class="w-1/4">
                                            <img class="img-fluid" style="max-width:20%;" src="/images/icon_blog.png" alt="Blog Icon">
                                        </div>
                                        <div class="w-3/4">
                                            <h4 class="text-xl font-semibold">{{ $post->title }}</h4>
                                            <small class="text-gray-500">Publicado em {{ $post->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <p class="mt-4">{{ $post->content }}</p>

                                     <!-- Exibir Comentários -->
                                    <div class="bg-gray-100 p-2  rounded-md">
                                    <h4 class="mt-6 mb-4">Comentários:</h4>


                                     @if ($post->comments->isEmpty())
                                            <p>Sem comentários até agora.</p>
                                        @else
                                            @foreach ($post->comments as $comment)
                                                <div class="bg-white border-b-2 comment mb-3">
                                                    <div class="ml-2">

                                                        <img  style="width:30px;"src="{{ $comment->user->profile_picture == null ? asset('storage/profile_pictures/perfil.jpg') : asset('storage/' . $comment->user->profile_picture )}}" class="h-8 w-8 rounded-full" alt="Foto de perfil">
                                                        <strong>{{ $comment->user->name}}</strong> <small>{{ $comment->created_at->format('d/m/Y H:i') }}</small>

                                                     </div>


                                                    <p>{!!$comment->content!!}</p>

                                                </div>
                                            @endforeach
                                        @endif

                                    <!-- Formulário para Adicionar Comentário -->
                                    <h4 class="mt-6 mb-4">Deixe seu comentário:</h4>
                                    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="container col-md-8">
                                        @csrf

                                        <input type="text" id="user_id" value="{{ Auth::user()->id }}" name="user_id" hidden />
                                        <div class="mb-3 row">
                                            <label for="author" class="col-form-label col-md-2">Nome:</label>
                                            <div class="col-md-10">
                                                <input type="text" name="author" class="form-control" required value="{{ Auth::user()->name }}">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="content" class="col-form-label col-md-2">Comentário:</label>
                                            <div class="col-md-10">
                                                <textarea name="content" class="form-control" rows="4" required></textarea>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Enviar</button>
                                        </div>
                                    </form>
                                    </div>
                                    <hr class="mt-4">
                                </div>
                                    @endforeach
                                    @else
                                        <p>Nenhum post encontrado.</p>

                                        <hr class="mt-4">
                                    @endif



                                <br><br>
                                <div class="p-6 bg-gray-300 text-gray-900">
                                    <div class="p-3 bg-gray-188 rounded-lg">
                                         <!-- Links de paginação -->
                                    {{ $posts->links() }}

                                    </div>
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
