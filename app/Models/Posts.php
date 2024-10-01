<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    return $this->hasMany(Comments::class,'post_id');


}

}
