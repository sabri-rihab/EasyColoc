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
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('payer_id')->constrained()->nullOnDelete();
        });

        // Migrate existing data
        if (Schema::hasColumn('expenses', 'category')) {
            $cats = DB::table('categories')->get()->keyBy('slug');
            foreach ($cats as $slug => $cat) {
                DB::table('expenses')
                  ->where('category', $slug)
                  ->update(['category_id' => $cat->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
