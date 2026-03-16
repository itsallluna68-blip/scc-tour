<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingAdmin = DB::table('tblusers')
            ->where('username', 'admin')
            ->first();

        if (!$existingAdmin) {
            DB::table('tblusers')->insert([
                'fname' => 'Reighn',
                'mname' => 'C.',
                'lname' => 'Ortega',
                'username' => 'admin',
                'password' => Hash::make('ann12345'),
                'usertype' => 'admin',
                'status' => 'active'
            ]);
            $this->command->info('Admin created successfully.');
        } else {
            $this->command->warn('Admin already exists, skipping creation.');
        }
    }
}
