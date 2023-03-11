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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('community_id')->constrained();
            $table->string('title');
            $table->text('purpose')->nullable();
            $table->text('indicators')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('contact_information')->nullable();
            $table->text('geographical_setting')->nullable();
            $table->text('economic_setting')->nullable();
            $table->text('social_setting')->nullable();
            $table->text('religious_setting')->nullable();
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
        Schema::dropIfExists('projects');
    }
};
