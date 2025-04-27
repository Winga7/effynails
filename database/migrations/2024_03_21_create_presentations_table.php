<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('presentations', function (Blueprint $table) {
      $table->id();
      $table->text('content')->nullable();
      $table->text('about_title')->nullable();
      $table->text('about_content')->nullable();
      $table->text('experience_title')->nullable();
      $table->text('experience_content')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('presentations');
  }
};
