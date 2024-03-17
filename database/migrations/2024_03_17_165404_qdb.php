<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_channels', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('channel');
            $table->integer('quote_count')->default(0);
        });

        Schema::create('quote_content', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('channel_id')->unsigned();
            $table->integer('quote_id')->unsigned()->unique();
            $table->string('author_id');
            $table->text('content');
            $table->integer('view_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('quote_votes', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('author_id')->unsigned();
            $table->integer('quote_id')->unsigned();
            $table->integer('vote')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_votes');
        Schema::dropIfExists('quote_content');
        Schema::dropIfExists('quote_channels');
    }
};