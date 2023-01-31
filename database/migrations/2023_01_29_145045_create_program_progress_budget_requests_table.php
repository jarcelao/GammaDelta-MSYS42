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
        Schema::create('program_progress_budget_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('program_progress_id')->constrained('program_progress');
            $table->string('account')->nullable();
            $table->float('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_progress_budget_requests');
    }
};
