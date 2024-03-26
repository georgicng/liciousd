<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('options', function (Blueprint $table) {
            $table->boolean('is_sys_defined')->nullable()->default(0)->after('type');
        });
        DB::table('options')
            ->insert([
                'code'              => 'config',
                'admin_name'        => 'Config',
                'type'              => 'json',
                'is_sys_defined'   => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('is_sys_defined');
        });
    }
};
