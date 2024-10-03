<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(true)->default(null);
            $table->text('description');
            $table->enum('type', ['amount', 'percentage'])->default('percentage');
            $table->boolean('family')->default(false);
            $table->boolean('recurring')->default(false);
            $table->decimal('amount')->nullable(true)->default(0.00);
            $table->integer('percentage')->nullable(true)->default(0);
            $table->integer('use_limit')->nullable(true)->default(1);
            $table->integer('max_discount_amount')->nullable(true)->default(0);
            $table->dateTime('start_date')->nullable(true)->default(null);
            $table->dateTime('end_date')->nullable(true)->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};