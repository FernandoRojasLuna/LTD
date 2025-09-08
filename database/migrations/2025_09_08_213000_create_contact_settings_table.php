<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->text('address')->nullable();
            $table->json('phones')->nullable();
            $table->json('emails')->nullable();
            $table->json('socials')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('map_embed')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_settings');
    }
};
