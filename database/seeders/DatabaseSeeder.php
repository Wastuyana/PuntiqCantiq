<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleOwner = Role::create(['name' => 'owner']);
        $roleStaff = Role::create(['name' => 'admin']);

        $userOwner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);
        $userOwner->assignRole($roleOwner);

        $userStaff = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $userStaff->assignRole($roleStaff);

        $this->call([
        ProdukSeeder::class,
        BahanBakuSeeder::class,
        SettingSeeder::class,
    ]);
    }
}
