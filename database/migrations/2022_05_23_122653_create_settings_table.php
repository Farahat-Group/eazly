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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('days_of_work');
            $table->string('start_work_time');
            $table->string('end_work_time');
            $table->string('start_discount_time1');
            $table->string('start_discount_time2');
            $table->float('first_discount_rate1');
            $table->float('second_discount_rate2');
            $table->float('lat');
            $table->float('lon');
            $table->text('allowed_absence_days');
            $table->text('company_name');
            $table->foreignId('manager_id')->constrained('mangers');
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
        Schema::dropIfExists('settings');
    }
};
