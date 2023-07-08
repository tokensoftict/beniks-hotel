<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClothServiceMappersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloth_service_mappers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("laundry_service_id")->constrained()->cascadeOnDelete();
            $table->foreignId("cloth_id")->constrained()->cascadeOnDelete();
            $table->decimal('price')->default(0);
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
        Schema::dropIfExists('cloth_service_mappers');
    }
}
