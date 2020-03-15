<?php

use Illuminate\Database\Seeder;
use App\Models\CreditAccount;

class CreditAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CreditAccount::class, 150)->create();
    }
}
