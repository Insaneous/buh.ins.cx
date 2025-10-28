<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User 
        $admin = User::firstOrCreate(
            ['email' => 'admin@ins.cx'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Categories
        $categories = [
            'income' => [
                'Заработная плата',
                'Иные доходы',
            ],
            'expense' => [
                'Продукты питания',
                'Транспорт',
                'Мобильная связь',
                'Интернет',
                'Развлечения',
                'Другое',
            ],
        ];

        foreach ($categories as $type => $names) {
            foreach ($names as $name) {
                Category::firstOrCreate([
                    'type' => $type,
                    'name' => $name,
                ]);
            }
        }

        $this->command->info('✅ Admin user and categories have been seeded.');
    }
}
