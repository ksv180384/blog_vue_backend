<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('название статуса');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort')->default(0);

        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default(null)->nullable()->comment('название поста');

            $table->text('content')->default(null)->nullable()->comment('текст поста');
            $table->unsignedBigInteger('status_id')->nullable()->default(null)->comment('статус поста');
            $table->unsignedBigInteger('author_id')->nullable()->comment('автор поста');
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('post_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_statuses');
    }
}
