<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('yarn_quotations', function (Blueprint $table) {
            $table->date('order_date')->nullable()->after('status');
        });
        Schema::table('dyed_quotations', function (Blueprint $table) {
            $table->date('order_date')->nullable()->after('status');
        });
        Schema::table('netting_quotations', function (Blueprint $table) {
            $table->date('order_date')->nullable()->after('status');
        });
        Schema::table('dyeing_quotations', function (Blueprint $table) {
            $table->date('order_date')->nullable()->after('status');
        });
        Schema::table('accessories_quotations', function (Blueprint $table) {
            $table->date('order_date')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('yarn_quotations', function (Blueprint $table) {
            $table->dropColumn('order_date');
        });
        Schema::table('dyed_quotations', function (Blueprint $table) {
            $table->dropColumn('order_date');
        });
        Schema::table('netting_quotations', function (Blueprint $table) {
            $table->dropColumn('order_date');
        });
        Schema::table('dyeing_quotations', function (Blueprint $table) {
            $table->dropColumn('order_date');
        });
        Schema::table('accessories_quotations', function (Blueprint $table) {
            $table->dropColumn('order_date');
        });
    }
};
