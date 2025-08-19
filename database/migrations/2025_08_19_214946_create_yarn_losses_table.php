<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('yarn_losses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('yarn_quotation_id');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit')->default('kg');
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('yarn_losses');
    }
};
