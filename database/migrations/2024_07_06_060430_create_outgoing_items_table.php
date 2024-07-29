<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingItemsTable extends Migration
{
    public function up()
    {
        Schema::create('outgoing_items', function (Blueprint $table) {
            $table->id();
            $table->string('outgoing_code');
            $table->string('item_code');
            $table->integer('quantity');
            $table->date('outgoing_date');
            $table->string('destination', 100);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('outgoing_items');
    }
}
