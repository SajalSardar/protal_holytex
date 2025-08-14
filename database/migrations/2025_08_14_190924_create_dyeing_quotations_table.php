<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('dyeing_quotations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->string('order_number')->nullable();
            $table->bigInteger('dyeing_factory_id')->nullable();
            $table->string('po_number')->nullable()->index();
            $table->string('style')->nullable()->index();
            $table->decimal('quantity', 10)->nullable();
            $table->decimal('price', 10)->nullable();
            $table->decimal('total_price', 10)->nullable();
            $table->integer('delivery_point_id')->nullable();
            $table->string('status')->default('pending');
            $table->date('approximate_delivery_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('purchase_date')->nullable();
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
        Schema::dropIfExists('dyeing_quotations');
    }
};
