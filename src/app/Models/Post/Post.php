<?php

namespace App\Models\Post;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'preview',
        'preview_img',
        'content',
        'hidden',
        'author_id',
        'status_id',
    ];

    protected $appends = ['created_at_humans'];

    /**
     * Автор поста
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function author(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    /**
     * Статус поста
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function status(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PostStatus::class, 'id', 'status_id');
    }

    /**
     * Родительские комментарии, исключены ответы к комментариям
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentsParent(){
        return $this->hasMany(PostComment::class)->orderByDesc('created_at')->limit(3);
    }

    /**
     * Лайки поста
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function up(){
        return $this->hasMany(PostUp::class)->where('up', '=', 1);
    }

    /**
     * Дислайки поста
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function down(){
        return $this->hasMany(PostUp::class)->where('up', '=', 2);
    }

    /**
     * Картинки поста
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(){
        return $this->hasMany(PostImage::class);
    }

    public function useUating(){
        $userId = Auth::check() ? Auth::id() : 0;
        return $this->hasOne(PostUp::class)->where('user_id', '=', $userId);
    }

    public function getCreatedAtHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getRatingAttribute()
    {
        return $this->up_count - $this->down_count;
    }

}
