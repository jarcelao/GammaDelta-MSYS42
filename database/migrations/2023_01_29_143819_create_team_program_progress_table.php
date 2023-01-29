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
        Schema::create('team_program_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained();
            $table->foreignId('program_progress_id')->constrained();
            $table->string('active_status');
            $table->integer('cycle_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_program_progress');
    }
};
