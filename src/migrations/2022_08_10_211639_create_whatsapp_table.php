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
         Schema::create('whatsapp', function (Blueprint $table) {
            $table->id();
            $table->string("telf",20)->nullable();
            $table->text("txt")->nullable();
            $table->string("img")->nullable();
            $table->string("aud")->nullable();
            $table->string("pdf")->nullable();
            $table->string("mp4")->nullable();
            $table->tinyIntesger("state")->default(0);
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
        Schema::dropIfExists('whatsapp');
    }
};
