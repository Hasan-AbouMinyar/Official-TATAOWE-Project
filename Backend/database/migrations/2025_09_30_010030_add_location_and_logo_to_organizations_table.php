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
        // Check if columns already exist before adding them
        if (!Schema::hasColumn('organizations', 'location')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->string('location')->nullable()->after('address');
            });
        }
        
        if (!Schema::hasColumn('organizations', 'logo')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->string('logo')->nullable()->after('photo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['location', 'logo']);
        });
    }
};
