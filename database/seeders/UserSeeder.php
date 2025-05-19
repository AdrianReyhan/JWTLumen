<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => app('hash')->make('password123'),
                'role' => 'admin',
                'api_token' => hash('sha256', Str::random(60)),
                'token_expiration' => Carbon::now()->addDays(7),
            ],
            [
                'name' => 'Penulis ',
                'email' => 'penulis@gmail.com',
                'password' => app('hash')->make('password123'),
                'role' => 'penulis',
                'api_token' => hash('sha256', Str::random(60)),
                'token_expiration' => Carbon::now()->addDays(7),
            ],
            [
                'name' => 'Editor ',
                'email' => 'editor@gmail.com',
                'password' => app('hash')->make('password123'),
                'role' => 'editor',
                'api_token' => hash('sha256', Str::random(60)),
                'token_expiration' => Carbon::now()->addDays(7),
            ],
            [
                'name' => 'Pembaca',
                'email' => 'pembaca@gmail.com',
                'password' => app('hash')->make('password123'),
                'role' => 'pembaca',
                'api_token' => hash('sha256', Str::random(60)),
                'token_expiration' => Carbon::now()->addDays(7),
            ]
        ]);
    }
}
