<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nullable so incidents created before assets existed are kept as they are.
     */
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->foreignId('asset_id')->nullable()->after('requester_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asset_id');
        });
    }
};
