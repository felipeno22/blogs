<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Posts extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'title',
        'content',
        'slug',
        'status',// 'draft', 'published' rascunho ou publicado
        'user_id',
        'category_id',

    ];


public function user()
{
    return $this->belongsTo(User::class,'user_id');
}



    public function categories()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function tags()
{
    return $this->belongsToMany(Tag::class);
}


public function comments(){
    return $this->hasMany(Comments::class,'post_id')->orderBy('created_at', 'desc');


}

    public function likes()
    {
        return $this->hasMany(Like::class,'post_id');
    }


    protected static function boot()
    {
        parent::boot();

        // Gera o slug automaticamente antes de salvar o post
        static::creating(function ($post) {
           // $post->slug = Str::slug($post->title);
           $slug = Str::slug($post->title);
           $originalSlug = $slug;

           // Verifica se o slug já existe e incrementa se necessário
           $count = 1;
           while (Posts::where('slug', $slug)->exists()) {
               $slug = "{$originalSlug}-{$count}";
               $count++;
           }

           $post->slug = $slug;
       });


    }


}
