<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_responses', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('assignment_id')->index()->references('id')->on('assignments')->onDelete('cascade');
            $table->uuid('answer_id')->index()->references('id')->on('answers')->onDelete('cascade');
            $table->uuid('question_id')->index()->references('id')->on('questions')->onDelete('cascade');
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
        Schema::dropIfExists('assignment_responses');
    }
}
