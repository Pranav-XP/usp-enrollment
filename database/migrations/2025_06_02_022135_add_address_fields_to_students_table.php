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
        Schema::table('students', function (Blueprint $table) {
            $table->string('postal_address', 500)->nullable()->after('phone');
            $table->string('residential_address', 500)->nullable()->after('postal_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop both columns if rolling back
            $table->dropColumn('residential_address');
            $table->dropColumn('postal_address');
        });
    }
};
