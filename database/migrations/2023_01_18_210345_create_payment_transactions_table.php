<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained();
            $table->string('tamara_order_id');
            $table->string('capture_id')->nullable();
            $table->string('checkout_id');
            $table->string('order_id_refrence');
            $table->string('checkout_url');
            $table->enum('status', [
                'new',
                'approved',
                'expired',
                'authorized',
                'canceled',
                'updated',
                'fully_captured',
                'full_refunded',
                'partially_captured',
                'partially_refunded',
            ])->default('new');
            $table->text('data');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};
