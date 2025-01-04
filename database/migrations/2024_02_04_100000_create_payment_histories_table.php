<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'payment_histories',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('module', 50)->index();
                $table->nullableUuidMorphs('paymentable');
                $table->uuidMorphs('payer');
                $table->string('payment_id', 150)->nullable();
                $table->string('payment_method', 50);
                $table->string('status', 50)->default(PaymentHistory::STATUS_PROCESSING);
                $table->json('data')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
