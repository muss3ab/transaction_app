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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->string('payment_type');
            $table->string('payment_amount');
            $table->string('payment_status');
            $table->string('payment_currency');
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\Transaction::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
