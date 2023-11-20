<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Hapra Dev',
                'username' => 'hapra_dev',
                'email' => 'contact.hapra@gmail.com',
                'role_id' => RoleEnum::DEVELOPER,
                'password' => bcrypt('yscdev')
            ],
            [
                'name' => 'Asri Yuniati',
                'username' => 'asri_yuni',
                'email' => 'asriyuni@gmail.com',
                'role_id' => RoleEnum::OWNER,
                'password' => bcrypt('yscown')
            ]
        ];

        DB::transaction(fn () => collect(
            $users
        )->each(fn ($user) => User::create($user)));
    }
}
