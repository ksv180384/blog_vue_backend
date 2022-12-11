<?php

namespace App\Models\Post;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
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
    public function down(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
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

    /**
     * @return mixed
     */
    public function getCreatedAtHumansAttribute(): string
    {
        $daysSinceCreated = Carbon::now()->diffInDays($this->created_at);
        if($daysSinceCreated >= 1){
            return $this->created_at->format('d.m.Y H:i');
        }

        return $this->created_at->diffForHumans();
    }

    public function getRatingCountAttribute(): int
    {
        return $this->up_count - $this->down_count;
    }

    public function getFirstImageAttribute()
    {
        return $this->images ? $this->images->first() : null;
    }

    public function scopePostsList($query)
    {
        $userId = Auth::check() ? Auth::id() : 0;
        // Запрос формирует таблицу с учетом рейтига поста
        $sqlPostRating = '
            (
                SELECT `posts`.`id`, (COUNT(`up_post`.`up`) - COUNT(`down_post`.`up`)) AS `rating`
                    FROM `posts`
                    LEFT JOIN `post_ups` AS `up_post` ON `up_post`.`post_id` = `posts`.`id` AND `up_post`.`up` = 1
                    LEFT JOIN `post_ups` AS `down_post` ON `down_post`.`post_id` = `posts`.`id` AND `down_post`.`up` = 2
                    GROUP BY `posts`.`id`
            ) AS `ratings`
        ';

        return $query->select([
            'posts.id',
            'posts.title',
            DB::raw('CONCAT(SUBSTRING(`posts`.`content`, 1, 600), "...") AS `content`'),
            'posts.status_id',
            'posts.author_id',
            'posts.created_at',
            'posts.updated_at',
            'ratings.rating'
        ])
            ->join(DB::raw($sqlPostRating), 'ratings.id', '=', 'posts.id')
            ->when($userId, function ($q) use ($userId) {
                return $q
                    ->addSelect('post_ups.up')
                    ->leftJoin('post_ups', function($join) use ($userId) {
                    $join->on('post_ups.post_id', '=', 'posts.id')->where('post_ups.user_id', '=', $userId);
                });
            })
            ->with(['author:id,name,avatar', 'status:id,title', 'useRating', 'images:id,post_id,path'])
            ->withCount(['up', 'down']);
    }

}
