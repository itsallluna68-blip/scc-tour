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
            ->where('usertype', 'Superadmin')
            ->first();

        if (!$existingAdmin) {
            DB::table('tblusers')->insert([
                'fname' => 'Reighn',
                'mname' => 'C.',
                'lname' => 'Ortega',
                'username' => 'Admin',
                'password' => Hash::make('ann12345'),
                'usertype' => 'Superadmin',
                'status' => '1',
            ]);
            $this->command->info('Superadmin created successfully.');
        } else {
            $this->command->warn('Superadmin already exists, skipping creation.');
        }
    }
}
