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
        if (!Schema::hasTable('table_usedcards')) {
            Schema::create('table_usedcards', function (Blueprint $table) {
                $table->id();
                $table->string('transactionId')->unique();
                $table->string('userId');
                $table->string('username');
                $table->string('quantity');
                $table->string('pin');
                $table->string('serialNumber');
                $table->string('amount');
                $table->string('network');
                $table->string('status');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_usedcards');
    }
};
