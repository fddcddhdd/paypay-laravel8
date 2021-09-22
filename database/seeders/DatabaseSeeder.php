<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => env('ADMIN_EMAIL', 'admin@example.com'),
                'email_verified_at' => now(),
                'password' => \Hash::make(env('ADMIN_PASSWORD', 'password')),
                'admin' => true,
                'created_at' => now(),
                'updated_at' => now()
            // ],[
            //     'name' => 'test',
            //     'email' => 'test@example.com',
            //     'email_verified_at' => now(),
            //     'password' => \Hash::make('kRbrVc_dBbDh7'),
            //     'admin' => false,
            //     'created_at' => now(),
            //     'updated_at' => now()
            ]
        ]);
    }
}
