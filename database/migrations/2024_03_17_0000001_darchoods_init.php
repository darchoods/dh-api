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
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id')->primary();
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->json('nicks');
            $table->bool('use_nick')->default(0);
            $table->string('email')->unique();
            $table->string('remember_token', 100);
            $table->string('weather');
            $table->bool('verified')->default(0);
            $table->bool('disabled')->default(0);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->index()->unique();
            $table->string('name')->unique();
            $table->string('description');
            $table->integer('level')->default(0);
            $table->boolean('active')->default(0);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id')->index()->unique();
            $table->string('name')->unique();
            $table->string('description');
            $table->boolean('active')->default(0);
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->unique(['user_id', 'permission_id']);
        });

        Schema::create('role_users', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('role_id');
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('api_auth', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('key');
            $table->string('description');
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('api_auth');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('users');
    }
};
