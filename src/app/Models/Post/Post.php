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
        'content',
        'status_id',
        'author_id',
    ];

    protected $appends = ['created_at_humans', 'first_image'];

    /**
     * Автор поста
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    function author(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    /**
     * Картинки поста
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostImage::class, 'post_id', 'id');
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
    public function commentsParent(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostComment::class)->orderByDesc('created_at')->limit(3);
    }

    /**
     * Лайки поста
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function up(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function useRating(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
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

    public function getFirstImageAttribute()
    {
        return $this->images ? $this->images->first() : null;
    }

    public function scopePostsList($query, $userId)
    {
        return $query->select([
            'posts.id',
            'posts.title',
            'posts.content',
            'posts.status_id',
            'posts.author_id',
            'posts.created_at',
            'posts.updated_at',
            'post_ups.up'
        ])
            ->leftJoin('post_ups', function($join) use ($userId) {
                $join->on('post_ups.post_id', '=', 'posts.id')->where('post_ups.user_id', '=', $userId);
            })
            ->with(['author:id,name,avatar', 'status:id,title', 'useRating', 'images:id,post_id,path'])
            ->withCount(['up', 'down']);
    }

}
