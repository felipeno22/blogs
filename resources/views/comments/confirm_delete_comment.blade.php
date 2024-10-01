<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Excluir Comentário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="bg-white p-5 shadow-lg rounded-lg">

                <!-- Mensagem de Confirmação -->
                <p class="alert alert-warning text-center">
                    Gostaria mesmo de excluir um comentário <strong>{{ $comment->content }}</strong>?<br>
                    Esta ação não pode ser desfeita.
                </p>

                <!-- Formulário para Confirmar Exclusão -->
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="text-center">
                    @csrf
                    @method('DELETE')

                    <!-- Botões de Ação -->
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                    <a href="{{ route('comments.index') }}" class="btn btn-secondary">Não, Voltar</a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
