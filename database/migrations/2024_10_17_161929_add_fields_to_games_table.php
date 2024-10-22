<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->integer('year_published')->nullable();
            $table->integer('rank')->nullable();
            $table->float('bayes_average')->nullable();
            $table->float('average')->nullable();
            $table->integer('users_rated')->nullable();
            $table->boolean('is_expansion')->nullable();  // Use boolean instead of binary for true/false values
            $table->integer('abstracts_rank')->nullable();
            $table->integer('cgs_rank')->nullable();
            $table->integer('childrens_games_rank')->nullable();
            $table->integer('family_games_rank')->nullable();
            $table->integer('party_games_rank')->nullable();
            $table->integer('strategy_games_rank')->nullable();
            $table->integer('thematic_rank')->nullable();
            $table->integer('wargames_rank')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn([
                'year_published',
                'rank',
                'bayes_average',
                'average',
                'users_rated',
                'is_expansion',
                'abstracts_rank',
                'cgs_rank',
                'childrens_games_rank',
                'family_games_rank',
                'party_games_rank',
                'strategy_games_rank',
                'thematic_rank',
                'wargames_rank'
            ]);
        });
    }
};
