<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('netting_quotation_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('netting_quotation_id')->nullable();
            $table->decimal('quantity', 10)->nullable();
            $table->integer('delivery_point_id')->nullable();
            $table->date('delivery_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('netting_quotation_items');
    }
};
