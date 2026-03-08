<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if a Superadmin already exists
        $existingAdmin = DB::table('tblusers')
            ->where('usertype', 'Superadmin')
            ->first();

        if (!$existingAdmin) {
            DB::table('tblusers')->insert([
                'fname' => 'Reighn',
                'mname' => 'C.',
                'lname' => 'Ortega',
                'username' => 'Admin',
                'password' => Hash::make('ann12345'), // hashed password
                'usertype' => 'Superadmin',
                'status' => '1',
            ]);

            $this->command->info('Superadmin created successfully.');
        } else {
            $this->command->warn('Superadmin already exists, skipping creation.');
        }
    }
}
