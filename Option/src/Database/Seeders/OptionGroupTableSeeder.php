<?php

namespace Gaiproject\Option\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionGroupTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('option_groups')->delete();

        DB::table('option_group_mappings')->delete();

        DB::table('option_groups')->delete();

        DB::table('option_groups')->insert([
            [
                'id'                  => 1,
                'name'                => 'General',
                'column'              => 1,
                'is_user_defined'     => 0,
                'position'            => 1,
                'attribute_family_id' => 1,
            ],
        ]);
    }
}
