<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = new User();
        $user1->name = 'Richard';
        $user1->email = 'richard@importer.com';
        $user1->password = Hash::make('richard_1234');
        $user1->save();

        DB::table('users')->insert(
            [
                [
                    'name' => 'Manly',
                    'email' => 'manly@importer.com',
                    'password' => Hash::make('manly_1234'),
                ],
                [
                    'name' => 'Lori',
                    'email' => 'lori@importer.com',
                    'password' => Hash::make('lori_1234'),
                ],
                [
                    'name' => 'Jhen',
                    'email' => 'jhen@importer.com',
                    'password' => Hash::make('jhen_1234'),
                ],
            ]
        );
    }
}
