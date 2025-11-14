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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('symbol');
            $table->integer('shares')->default(0);
            $table->decimal('avg_price', 12, 4)->default(0);
            $table->timestamps();
        });

        Schema::create('user_balances', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->decimal('cash', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('user_balances');
    }
};
