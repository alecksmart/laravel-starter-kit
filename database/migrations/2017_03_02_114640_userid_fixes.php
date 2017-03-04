<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UseridFixes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['post_author_name', 'post_author_email']);
            $table->integer('user_id')->unsigned()->after('id');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['comment_author_name', 'comment_author_email']);
            $table->integer('user_id')->unsigned()->after('id');
        });

        DB::unprepared('UPDATE `posts` SET `user_id` = 1');
        DB::unprepared('UPDATE `comments` SET `user_id` = 1');

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('posts', function (Blueprint $table) {
            $table->string('post_author_name', 100);
            $table->string('post_author_email', 100);
            $table->dropForeign('posts_user_id_foreign');
            $table->dropColumn(['user_id']);
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->string('comment_author_name', 100);
            $table->string('comment_author_email', 100);
            $table->dropForeign('comments_user_id_foreign');
            $table->dropColumn(['user_id']);
        });
    }
}
