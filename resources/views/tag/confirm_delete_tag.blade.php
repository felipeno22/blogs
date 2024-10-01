<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Excluir Tags') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="bg-white p-5 shadow-lg rounded-lg">

                <!-- Mensagem de confirmação -->
                <p class="alert alert-warning text-center">
                    Gostaria mesmo de excluir a tag <strong>{{ $tag->name }}</strong>?<br>
                    Não será possível desfazer a ação.
                </p>

                <!-- Formulário de confirmação de exclusão -->
                <form action="{{ route('tag.destroy', $tag->id) }}" method="POST" class="d-flex justify-content-center">
                    @csrf
                    @method('DELETE')

                    <!-- Botões de ação -->
                    <button type="submit" class="btn btn-danger ">Sim, Excluir</button>
                    <a href="{{ route('tag.index') }}" class="btn btn-secondary">Não, Voltar</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
