<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->date('run_at'); // date when to be run
            $table->string('action'); // action class name
            $table->string('who_object'); // eg. company
            $table->integer('who_id'); // 3 = company Id
            $table->json('parameters'); // parameters to run action class with
            $table->enum('status', ['done', 'in_progress', 'new'])->default('new');
            $table->string('last_message'); // useful if error ocured
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
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
        Schema::drop('schedules');
    }
}
