<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // stored as timestamp with timezone
            $table->timestampTz('date')->useCurrent();
            // type: income or expense
            $table->enum('type', ['income', 'expense'])->index();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            // decimal sized to hold big sums safely
            $table->decimal('amount', 14, 2);
            $table->text('comment')->nullable();
            $table->timestamps();

            // useful index for common queries
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
