<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

namespace LarabizCMS\Modules\Payment\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'payment_methods',
            function (Blueprint $table) {
                $table->id();
                $table->string('type', 50)->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->json('data')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
