<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('title')->nullable();
            $table->mediumText('description')->nullable();
            $table->enum('states', ['pending', 'completed'])->default('pending');
            $table->DateTime('due_date');
            $table->timestamps();

            // When main task is remove subtask also remove
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_tasks');
    }
}
