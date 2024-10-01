<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("postsTable");
        switching = true;
        dir = "asc"; // Definindo ordem padrão como ascendente

        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
    </script>


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="bg-white p-5 shadow-lg rounded-lg">
                <h1 class="mb-4">Posts</h1>

                <!-- Botão de Adicionar tag -->
                <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Adicionar Novo Post</a>

                <!-- Formulário de Pesquisa -->
                <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" placeholder="Pesquisar tag...">
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


    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <
                <th>Título</th>
                <th>Categoria</th>
                <th>Autor</th>
                <th>Status</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>

                <td>{{ $post->title }}</td>
                <td>{{ $post->categories->name }}</td>
                <td>{{ $post->user->name }}</td>
            <td>{{ $post->status == "draft" ? "Rascunho" : "Publicado" }}</td>
                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Editar</a>
                    <a href="{{ route('confirm.delete.posts', $post) }}" class="btn btn-danger btn-sm">Excluir</a>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</br>

<div class="p-6 bg-gray-300 text-gray-900">
    <div class="p-3 bg-gray-188 rounded-lg">
            <!-- Links de paginação -->
                {{ $posts->links() }}

    </div>
</div>
</div>
</div>
</div>
</x-app-layout>
