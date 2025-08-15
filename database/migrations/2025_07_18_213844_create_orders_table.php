<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable()->index();
            $table->string('po_number')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable()->index();
            $table->string('client_phone')->nullable();
            $table->date('order_date')->nullable();
            $table->date('approximate_delivery_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('client_address')->nullable();
            $table->text('ship_address')->nullable();
            $table->string('po_file')->nullable();
            $table->string('total_quantity')->nullable();
            $table->decimal('grand_total', 10)->nullable();
            $table->string('status')->default('processing');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
