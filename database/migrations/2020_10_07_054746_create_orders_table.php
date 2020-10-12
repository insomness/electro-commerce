<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('code');
            $table->string('status');
            $table->datetime('order_date');
            $table->datetime('payment_due');
            $table->string('payment_status');
            $table->decimal('base_total_price', 16, 2)->default(0);
            $table->decimal('tax_amount', 16, 2)->default(0);
            $table->decimal('tax_percent', 16, 2)->default(0);
            $table->decimal('shipping_cost', 16, 2)->default(0);
            $table->decimal('grand_total', 16, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->text('customer_address')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_province_id')->nullable();
            $table->string('customer_city_id')->nullable();
            $table->integer('customer_postcode')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('shipping_courier')->nullable();
            $table->string('shipping_service_name')->nullable();
            $table->foreignId('approved_by')->nullable()->references('id')->on('users');
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->references('id')->on('users');
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancelled_note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['code', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
