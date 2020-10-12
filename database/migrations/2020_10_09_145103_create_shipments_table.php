<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('order_id')->constrained();
            $table->string('track_number')->nullable();
            $table->string('status');
            $table->integer('total_qty');
            $table->integer('total_weight');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('province_id')->nullable();
            $table->string('city_id')->nullable();
            $table->integer('postcode')->nullable();
            $table->foreignId('shipped_by')->nullable()->constrained('users', 'id');
            $table->datetime('shipped_at')->nullable();
            $table->softDeletes();

            $table->index('track_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
