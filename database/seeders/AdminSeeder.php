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
            ->where('usertype', 'Admin')
            ->first();

        if (!$existingAdmin) {
            DB::table('tblusers')->insert([
                'fname' => 'Reighn',
                'mname' => 'C.',
                'lname' => 'Ortega',
                'username' => 'Admin',
                'password' => Hash::make('ann12345'),
                'usertype' => 'Admin',
                'status' => '1',
            ]);
            $this->command->info('Admin created successfully.');
        } else {
            $this->command->warn('Admin already exists, skipping creation.');
        }
    }
}
