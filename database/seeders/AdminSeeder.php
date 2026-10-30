<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\{User, Profile};

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create('id_ID');

        $profile = Profile::firstOrCreate(
            ['bio' => 'administrator'],
            [
                'alamat' => $faker->address,
                'umur'   => $faker->numberBetween(25, 45),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'admin',
                'email_verified_at' => now(),
                'password'          => Hash::make('12345678'),
                'remember_token'    => Str::random(10),
                'role'              => 'admin',
                'role_id'           => 2,
                'profile_id'        => $profile->id,
            ]
        );
    }
}
