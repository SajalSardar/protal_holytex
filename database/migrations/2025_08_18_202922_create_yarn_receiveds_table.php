<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('yarn_receiveds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('yarn_quotation_id');
            $table->string('po_number')->nullable()->index();
            $table->string('style')->nullable()->index();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('unit')->default('kg');
            $table->string('lot_number')->nullable();
            $table->string('bag_count')->nullable();
            $table->date('challan_date')->nullable();
            $table->string('challan_number')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('status')->default('received');
            $table->date('received_date')->nullable()->index();
            $table->integer('received_by')->nullable();
            $table->string('challan_file')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('yarn_factory_id')->nullable();
            $table->integer('netting_factory_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('yarn_receiveds');
    }
};
