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
        if (!Schema::hasTable('downlines')) {
            Schema::create('downlines', function (Blueprint $table) {
                $table->id();
                $table->string('userId');
                $table->string('owner');
                $table->string('downline');
                $table->string('fullname');
                $table->string('email');
                $table->string('rank');
                $table->string('package');
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
        Schema::dropIfExists('downlines');
    }
};
