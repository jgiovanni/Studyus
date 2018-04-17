<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('user_id')->index()->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url_id')->unique();
//            $table->string('type', 25);
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->dateTime('opened_at')->nullable();
//            $table->dateTime('completed_at');
            $table->boolean('public')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('assignables', function (Blueprint $table) {
            $table->uuid('task_id')->index();
            $table->uuid('assignable_id')->index();
            $table->string('assignable_type', 25);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('assignables');
    }
}
