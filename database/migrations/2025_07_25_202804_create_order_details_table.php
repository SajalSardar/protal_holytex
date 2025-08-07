<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable()->index();
            $table->string('order_number')->nullable()->index();
            $table->string('po_number')->nullable()->index();
            $table->string('style')->nullable()->index();
            $table->string('description')->nullable();
            $table->decimal('unit_quantity', 10)->nullable();
            $table->decimal('unit_price', 10)->nullable();
            $table->decimal('total_unit_price', 10)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('order_details');
    }
};
