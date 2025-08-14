<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('nettings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->string('po_number')->nullable()->index();
            $table->string('style')->nullable()->index();
            $table->decimal('quantity', 10)->nullable();
            $table->decimal('price', 10)->nullable();
            $table->decimal('total_price', 10)->nullable();
            $table->string('delivery_factory_type')->nullable();
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
        Schema::dropIfExists('nettings');
    }
};
