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
        Schema::create('colocations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('adresse');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('invitation_code')->unique(); // for easy invites
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocations');
    }
};
