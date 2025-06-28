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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('document_path')->nullable();
            $table->string('password');
            $table->string('security_question')->nullable();
            $table->string('security_answer')->nullable();
            $table->float('balance')->default(0);
            $table->integer('reward_points')->default(0);
            $table->float('travel_quota')->default(0);
            $table->date('rp_validity')->nullable();
            $table->date('tq_validity')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
};
