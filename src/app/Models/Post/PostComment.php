<?php

namespace App\Models\Post;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'post_id',
        'parent_id',
        'branch_id', // id первого комментария в ветке
        'comment',
        'status_id',
    ];

    protected $appends = ['created_at_humans'];

    /**
     * Автор комментария
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function author(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    /**
     * Автор комментария
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function post(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    /**
     * Комментарии
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostComment::class, 'branch_id', 'id');
    }

    /**
     * Лайки
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function up(){
        return $this->hasMany(PostCommentUp::class, 'comment_id', 'id')->where('up', '=', 1);
    }

    /**
     * Дислайки
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function down(){
        return $this->hasMany(PostCommentUp::class, 'comment_id', 'id')
            ->where('up', '=', 2);
    }

    /**
     * Статус комментария
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function status(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PostStatus::class, 'id', 'status_id');
    }

    public function getCreatedAtHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Считает рейтинг поста
     * @return mixed
     */
    public function getRatingAttribute()
    {
        return $this->up_count - $this->down_count;
    }

    /**
     * Скоуп для получения основных данных комментария поста
     * @param $query
     * @return mixed
     */
    public function scopeCommentsPost($query)
    {
        return $query
            ->with(['author:id,name,avatar'])
            ->withCount(['comments', 'up', 'down']);
    }
}

