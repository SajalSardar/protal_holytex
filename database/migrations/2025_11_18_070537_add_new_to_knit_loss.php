<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('netting_losses', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable()->after('id');
            $table->bigInteger('dyeing_quotation_id')->nullable()->after('netting_quotation_id');
            $table->string('delived_factory_type')->nullable()->after('dyeing_quotation_id')->comment('knitting,dyeing');
            $table->string('description')->nullable()->after('delived_factory_type');
            $table->string('po_number')->nullable()->after('delived_factory_type')->index();
            $table->string('style')->nullable()->after('delived_factory_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('netting_losses', function (Blueprint $table) {
            $table->dropColumn('style');
            $table->dropColumn('order_id');
            $table->dropColumn('description');
            $table->dropColumn('po_number');
            $table->dropColumn('dyeing_quotation_id');
            $table->dropColumn('delived_factory_type');
        });
    }
};
