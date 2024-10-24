<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            // Add a string field for the location
            $table->string('location')->nullable();

            // Add a foreign key to the users table
            $table->unsignedBigInteger('user_id')->nullable();

            // Define the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop the foreign key constraint and the user_id column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Drop the location column
            $table->dropColumn('location');
        });
    }
};
