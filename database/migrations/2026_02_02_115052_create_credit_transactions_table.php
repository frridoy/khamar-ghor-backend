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
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['earn', 'spend', 'refund', 'bonus', 'adjustment']);
            $table->enum('source', ['signup', 'post_unlock', 'purchase', 'admin']);
            $table->string('reference_id')->nullable();
            $table->bigInteger('credits');
            $table->bigInteger('balance_before')->default(0);
            $table->bigInteger('balance_after')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->index('user_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
