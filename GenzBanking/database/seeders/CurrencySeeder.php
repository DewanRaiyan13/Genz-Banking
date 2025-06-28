<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            ['code' => 'USD', 'exchange_rate' => 1.0000],
            ['code' => 'EUR', 'exchange_rate' => 0.8500],
            ['code' => 'GBP', 'exchange_rate' => 0.7500],
            ['code' => 'BDT', 'exchange_rate' => 85.0000],
        ]);
    }
}
