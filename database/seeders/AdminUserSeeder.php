<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ibrahimgandeel@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('059345640599##$$'), // ضع كلمة المرور الخاصة بك هنا
                'is_admin' => true, // تأكد من وجود حقل is_admin في جدول users
            ]
        );
    }
}
