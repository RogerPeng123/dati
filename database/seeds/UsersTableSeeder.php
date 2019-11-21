<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('slug', 'admin')->first();
        $user = Role::where('slug', 'user')->first();

        factory('App\User', 1)->create([
            'name' => '洗头只用海飞丝',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456')
        ])->each(function ($u) use ($admin) {
            $u->attachRole($admin);
        });

        factory('App\User', 2)->create([
            'password' => bcrypt('123456')
        ])->each(function ($u) use ($user) {
            $u->attachRole($user);
        });
    }
}