<?php

use Illuminate\Database\Seeder;
use App\Models\GradeGroup;

class GradeGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(GradeGroup::class, 50)->create();
    }
}
