<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Ensure you import your User model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example users to be inserted
        $users = [
            [
                'name' => 'raghad dwaik',
                'email' => 'raghad.dwaik@gmail.com',
                'password' => bcrypt('password123'),
                'phone_num'=>'0595442943',
                'address'=>'ramallah',
            ]
           
        ];

        // Insert users into the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
