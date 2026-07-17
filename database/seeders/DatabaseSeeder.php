<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            [
                'name'     => 'Admin Sistem',
                'email'    => 'admin@smartspend.test',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Kepala Divisi',
                'email'    => 'headdiv@smartspend.test',
                'password' => Hash::make('password'),
                'role'     => 'head_division',
            ],
            [
                'name'     => 'Staf Keuangan',
                'email'    => 'finance@smartspend.test',
                'password' => Hash::make('password'),
                'role'     => 'finance_staff',
            ],
            [
                'name'     => 'Rektor',
                'email'    => 'leader@smartspend.test',
                'password' => Hash::make('password'),
                'role'     => 'leader',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
