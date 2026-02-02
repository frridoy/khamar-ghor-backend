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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->comment('1=Active, 2=Inactive, 3=Suspended')->index()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index()->after('address');
        });
    }
};
