<?php

use Illuminate\Database\Seeder;
use App\Models\CreditTransaction;

class CreditTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CreditTransaction::class, 150)->create();
    }
}
