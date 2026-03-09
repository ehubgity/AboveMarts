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
        if (!Schema::hasTable('preordercards')) {
            Schema::create('preordercards', function (Blueprint $table) {
                $table->id();
                $table->string('transactionId')->unique();
                $table->string('userId');
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
        Schema::dropIfExists('preordercards');
    }
};
