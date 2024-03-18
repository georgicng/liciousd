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
        Schema::create('option_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_family_id')->unsigned();
            $table->string('name');
            $table->integer('column')->default(1);
            $table->integer('position');
            $table->boolean('is_user_defined')->default(1);

            $table->unique(['attribute_family_id', 'name']);
            $table->foreign('attribute_family_id')->references('id')->on('attribute_families')->onDelete('cascade');
        });

        Schema::create('option_group_mappings', function (Blueprint $table) {
            $table->integer('option_id')->unsigned();
            $table->integer('option_group_id')->unsigned();
            $table->integer('position')->nullable();

            $table->primary(['option_id', 'option_group_id']);
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->foreign('option_group_id')->references('id')->on('option_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_group_mappings');
        Schema::dropIfExists('option_groups');
    }
};
