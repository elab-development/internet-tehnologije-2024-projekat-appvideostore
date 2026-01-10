<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
    
            $table->string('title')->nullable();
            $table->text('content');
            $table->integer('rating')->unsigned()->check('rating >= 1 AND rating <= 10');
            $table->boolean('is_spoiler')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
        });
    }



   
    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
