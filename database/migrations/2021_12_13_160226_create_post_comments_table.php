<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('название статуса');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort')->default(0);
        });

        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable()->comment('автор комментария');
            $table->unsignedBigInteger('post_id')->comment('пост');
            $table->unsignedBigInteger('parent_id')->nullable()->default(null)->comment('родительский комментарий');
            $table->unsignedBigInteger('branch_id')->nullable()->default(null)->comment('id первого комментария в ветке');
            $table->text('comment')->comment('текст комментария');
            $table->unsignedBigInteger('status_id')->nullable()->comment('статус комментария');
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('post_comments')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('post_comments')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('post_comment_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['post_id']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('post_comment_statuses');
    }
}
