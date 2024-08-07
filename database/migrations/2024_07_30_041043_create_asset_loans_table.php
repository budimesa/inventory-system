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
        Schema::create('asset_loans', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->date('borrow_date');
            $table->date('planned_return_date');
            $table->text('loan_reason');
            $table->date('return_date')->nullable();
            $table->string('received_by')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('asset_loans');
    }
};
