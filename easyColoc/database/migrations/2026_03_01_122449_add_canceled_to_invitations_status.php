<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            DB::statement("ALTER TABLE invitations MODIFY COLUMN status ENUM('pending', 'accepted', 'refused', 'canceled') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            DB::statement("ALTER TABLE invitations MODIFY COLUMN status ENUM('pending', 'accepted', 'refused') DEFAULT 'pending'");
        });
    }
};
