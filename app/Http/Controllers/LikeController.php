<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost($id)
    {
        $post = Posts::findOrFail($id);



        // Verifica se o usuário já deu like neste post
        if ($post->likes()->where('user_id', Auth::user()->id)->exists()) {

            // se o like existe e remove
            $post->likes()->where('user_id', Auth::user()->id)->delete();
             // Conta o número total de curtidas para o post
            $likeCount = $post->likes()->count();

            $user_liked = $post->likes()->where('user_id', Auth::id())->exists();

                 return response()->json(['message' => 'Curtida removida.','like_count' =>$likeCount,'user_liked' =>$user_liked]);

                // return response()->json(['message' => 'Você já curtiu este post.', 'user_liked' => true], 403);
        }

        // Salva o like
        $post->likes()->create([
            'user_id' =>Auth::user()->id,
        ]);

         // Conta o número total de curtidas para o post
         $likeCount = $post->likes()->count();

         $user_liked = $post->likes()->where('user_id', Auth::id())->exists();

        return response()->json(['message' => 'Post curtido com sucesso.','like_count' =>$likeCount, 'user_liked' => $user_liked]);
    }

  /*  public function unlikePost($id)
    {
        $post = Posts::findOrFail($id);

        // Verifica se o like existe e remove
        $post->likes()->where('user_id', Auth::user()->id)->delete();

        return response()->json(['message' => 'Curtida removida.']);
    }*/
}
