<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('brand_id');
            $table->string('description')->nullable();
            $table->string('link')->nullable();
            $table->string('price')->nullable();
            $table->year('year')->nullable();
            $table->string('km')->nullable();
            $table->string('fuel')->nullable();
            $table->string('doors')->nullable();
            $table->string('color')->nullable();
            $table->string('plate_info')->nullable();
            $table->string('city')->nullable();
            $table->longText('trade_info')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('contact_info')->nullable();
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
        Schema::dropIfExists('cars');
    }
}
