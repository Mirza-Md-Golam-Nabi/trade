<?php
namespace Database\Seeders;

use App\Enums\CommonStatus;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = $this->currencies();

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }

    protected function currencies(): array
    {
        return [
            [
                'name'   => 'Bangladeshi Taka',
                'code'   => 'BDT',
                'symbol' => '৳',
                'status' => CommonStatus::Active,
            ],
            [
                'name'   => 'US Dollar',
                'code'   => 'USD',
                'symbol' => '$',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Euro',
                'code'   => 'EUR',
                'symbol' => '€',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'British Pound',
                'code'   => 'GBP',
                'symbol' => '£',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Japanese Yen',
                'code'   => 'JPY',
                'symbol' => '¥',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Swiss Franc',
                'code'   => 'CHF',
                'symbol' => 'CHF',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Canadian Dollar',
                'code'   => 'CAD',
                'symbol' => 'C$',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Australian Dollar',
                'code'   => 'AUD',
                'symbol' => 'A$',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Indian Rupee',
                'code'   => 'INR',
                'symbol' => '₹',
                'status' => CommonStatus::Inactive],
            [
                'name'   => 'Chinese Yuan',
                'code'   => 'CNY',
                'symbol' => '¥',
                'status' => CommonStatus::Inactive,
            ],
        ];
    }
}
