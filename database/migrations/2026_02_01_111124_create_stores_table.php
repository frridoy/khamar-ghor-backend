<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('division_id')->nullable()->constrained('divisions')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('thana_id')->nullable()->constrained('thanas')->onDelete('set null');
            $table->string('area_name')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            
            $table->string('trade_license_number')->nullable();
            
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_sales')->default(0);
            $table->integer('followers')->default(0);
            $table->date('established_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
