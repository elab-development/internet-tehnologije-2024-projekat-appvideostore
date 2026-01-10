<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('watchlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
    
            $table->enum('status', [
                'plan_to_watch', 
                'watching', 
                'watched', 
                'dropped'
            ])->default('plan_to_watch');
    
            $table->integer('rating')->nullable()->unsigned()->check('rating >= 1 AND rating <= 10');
            $table->text('note')->nullable();
            $table->timestamp('added_at')->nullable();       
            $table->timestamps();

            $table->unique(['user_id', 'movie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlist');
    }
};
