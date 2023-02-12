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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('community_id')->constrained();
            $table->string('title');
            $table->text('purpose')->nullable();
            $table->text('indicators')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('assumptions_and_risks')->nullable();
            $table->text('inputs')->nullable();
            $table->text('activities')->nullable();
            $table->text('outputs')->nullable();
            $table->text('outcomes')->nullable();
            $table->text('why')->nullable();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
};
