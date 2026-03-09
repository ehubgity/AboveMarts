<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_managers', function (Blueprint $table) {
            //
            $table->decimal('totalUsers')->default(0);
            $table->decimal('totalSpent', 15, 2)->default(0);
            $table->decimal('totalWithdraw', 15, 2)->default(0);
            $table->integer('totalPackage')->default(0);
            $table->decimal('totalDeposit', 15, 2)->default(0);
            $table->decimal('monthlySpent', 15, 2)->default(0);
            $table->decimal('monthlyWithdraw', 15, 2)->default(0);
            $table->integer('monthlyPackage')->default(0);
            $table->decimal('monthlyDeposit', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_managers', function (Blueprint $table) {
            //
            $table->dropColumn([
                'totalUsers',
                'totalSpent',
                'totalWithdraw',
                'totalPackage',
                'totalDeposit',
                'monthlySpent',
                'monthlyWithdraw',
                'monthlyPackage',
                'monthlyDeposit'
            ]);
        });
    }
};
