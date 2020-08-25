<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->decimal('amount', 8, 2);
            $table->decimal('paid', 8, 2)->nullable();
            $table->decimal('due', 8, 2)->nullable();
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('payment_mode_id');
            $table->unsignedBigInteger('user_id');
            $table->date('serve_at');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
