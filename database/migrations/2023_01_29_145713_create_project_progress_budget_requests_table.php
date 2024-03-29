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
        Schema::create('project_progress_budget_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_progress_id')->constrained('project_progress');
            $table->string('account')->nullable();
            $table->decimal('amount', $precision = 10, $scale = 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_progress_budget_requests');
    }
};
