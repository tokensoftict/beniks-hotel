<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laundry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("laundry_id")->constrained()->cascadeOnDelete();
            $table->foreignId("cloth_service_mapper_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warehousestore_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('department')->default('STORE');
            $table->bigInteger("quantity");
            $table->foreignId("customer_id")->nullable()->constrained()->nullOnDelete();
            $table->string("status")->default("DRAFT"); //["PAID","DRAFT","DISCOUNT","VOID","HOLD","COMPLETE"]
            $table->unsignedBigInteger("added_by");
            $table->date("invoice_date");
            $table->string("store")->default('quantity');
            $table->time("sales_time");
            $table->decimal("selling_price",20,5)->nullable();
            $table->decimal("profit",20,5)->nullable();
            $table->decimal("total_selling_price",20,5)->nullable();
            $table->string("discount_type")->nullable(); //['Fixed','Percentage','None']
            $table->decimal("discount_amount",20,5)->nullable();
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
        Schema::dropIfExists('laundry_items');
    }
}
