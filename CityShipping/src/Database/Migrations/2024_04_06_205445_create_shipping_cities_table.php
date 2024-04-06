<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_cities', function (Blueprint $table) {
            $table->id();
            $table->integer('country_id')->unsigned();
            $table->string('country_code')->nullable();
            $table->integer('state_id')->unsigned();
            $table->string('state_code')->nullable();
            $table->string('name')->nullable();
            $table->decimal('rate', 12, 4)->default(0);
            $table->boolean('status')->default(1);
            $table->json('additional')->nullable();
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('country_states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_cities');
    }
};
