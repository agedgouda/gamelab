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
        Schema::table('events', function (Blueprint $table) {
            // Add the date_selected field with foreign key reference to proposed_dates table
            $table->unsignedBigInteger('date_selected_id')->nullable();

            $table->foreign('date_selected')->references('id')->on('proposed_dates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop the foreign key and column if rolling back
            $table->dropForeign(['date_selected_id']);
            $table->dropColumn('date_selected');
        });
    }
};
