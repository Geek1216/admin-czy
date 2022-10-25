<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::query()->where('role', 'admin')->exists()) return;
        $user = User::query()->create([
            'name' => config('app.name'),
            'username' => 'admin',
            'password' => Hash::make($password = '12345678'),
            'role' => 'admin',
            'enabled' => true,
            'verified' => true,
            'business' => false,
        ]);
        $this->command->info(sprintf('User "%s" created with password "%s".', $user->username, $password));
    }
}
