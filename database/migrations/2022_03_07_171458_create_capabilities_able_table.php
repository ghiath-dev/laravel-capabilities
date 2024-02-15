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
        Schema::create('capabilities_able', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('capability_id');
            $table->foreign('capability_id')->references('id')->on('capabilities')->cascadeOnDelete();

            $table->morphs('model');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capabilities_able');
    }
};
