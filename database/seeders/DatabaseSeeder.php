<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AccountType;
use App\Models\Interest;
use App\Models\Post;
use App\Models\Reply;
use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $interests = [
            "🍎  Apples",
            "🍐  Pears",
            "🍏  Green Apples",
            "🥕  Carrots",
            "🍋  Lemons",
            "🍓 Strawberries",
            "🍇 Grapes",
            "🍅  Tomatoes",
            "🍊  Oranges"
        ];

        $accounts = [
            "A Farmer",
            "An Agri-expert",
            "An Aggregator",
            "Farm Insurance partner",
            "Quality input supplier",
            "Agribusiness partner",
            "Farm Intelligence",
        ];

        $accountsDbArray = [];
        foreach ($accounts as $account) {
            $accountsDbArray[] = [
                'name' => $account
            ];
        }




        $interestsDbArray = [];
        foreach ($interests as $interest) {
            $interestsDbArray[] = [
                'name' => $interest
            ];
        }
        AccountType::query()->insert($accountsDbArray);
//        Interest::query()->insert($interestsDbArray);

    }
}
