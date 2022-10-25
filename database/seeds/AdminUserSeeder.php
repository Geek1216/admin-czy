<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()->create([
            'name' => config('app.name'),
            'username' => 'admin',
            'password' => Hash::make($password = '12345678'),
            'role' => 'admin',
            'enabled' => true,
            'verified' => true,
        ]);
        echo sprintf('User "%s" created with password "%s".', $user->username, $password), PHP_EOL;
    }
}
