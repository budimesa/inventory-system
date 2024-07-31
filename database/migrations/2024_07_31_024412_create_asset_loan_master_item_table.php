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
        Schema::create('asset_loan_master_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_loan_id');
            $table->unsignedBigInteger('master_item_id');
            $table->timestamps();

            $table->foreign('asset_loan_id')->references('id')->on('asset_loans')->onDelete('cascade');
            $table->foreign('master_item_id')->references('id')->on('master_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_loan_master_item');
    }
};
