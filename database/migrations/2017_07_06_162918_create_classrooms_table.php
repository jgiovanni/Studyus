<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('name');
//            $table->text('description')->nullable();
            $table->string('url_id')->unique();
            $table->text('grades');
            $table->string('subject', 255);
            $table->timestamps();
        });

        Schema::create('classrooms_users', function (Blueprint $table) {
            $table->uuid('user_id')->index()->references('id')->on('users');
            $table->uuid('class_room_id')->index()->references('id')->on('classrooms');
            $table->string('permissions')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('classrooms_users');
    }
}
