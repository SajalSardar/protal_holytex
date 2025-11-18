<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('yarn_losses', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable()->after('id');
            $table->string('description')->nullable()->after('delived_factory_type');
            $table->string('po_number')->nullable()->after('delived_factory_type')->index();
            $table->string('style')->nullable()->after('delived_factory_type')->index();
        });
        Schema::table('yarn_store_stocks', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable()->after('id');
        });
        Schema::table('yarn_receiveds', function (Blueprint $table) {
            $table->bigInteger('order_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('yarn_losses', function (Blueprint $table) {
            $table->dropColumn('style');
            $table->dropColumn('order_id');
            $table->dropColumn('description');
            $table->dropColumn('po_number');
        });

        Schema::table('yarn_store_stocks', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
        Schema::table('yarn_receiveds', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
};
