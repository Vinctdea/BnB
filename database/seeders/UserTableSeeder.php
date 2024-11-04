<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = ['Ludovico', 'Tommaso', 'Vincenzo', 'Matteo', 'Alessandra'];
        $surname = ['ilMeglio', 'ilMeglio', 'ilMeglio', 'ilMeglio', 'laMeglio'];
        $i = 0;
        foreach ($users as $user) {

            $new_user = new User();
            $new_user->name = $user;
            $new_user->surname = $surname[$i];
            $new_user->email = "$user@admin.com";
            $new_user->password = Hash::make('12345678');
            $i++;
            $new_user->save();
        }
    }
}




