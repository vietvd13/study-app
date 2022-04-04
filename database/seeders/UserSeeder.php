<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = User::create([
           User::NAME => 'Nguyen Tien Nam',
           User::EMAIL => 'admin@gmail.com',
           User::PASSWORD => Hash::make('password'),
           User::CODE => 'ADMIN18325',
           User::PHONE => '0865628158',
           User::BLIND => 0,
           User::STATUS => 1
        ]);

        $accountRole = Role::findByName(ROLES['ADMIN']);
        $account->syncRoles($accountRole);

        $account = User::create([
            User::NAME => 'Vu Duck Viet',
            User::EMAIL => 'teacher@gmail.com',
            User::PASSWORD => Hash::make('password'),
            User::CODE => 'TEACH18325',
            User::PHONE => '0865628159',
            User::BLIND => 0,
            User::STATUS => 1
         ]);

         $accountRole = Role::findByName(ROLES['TEACHER']);
         $account->syncRoles($accountRole);

         $account = User::create([
            User::NAME => 'Vu Duck 02',
            User::EMAIL => 'student-not-blind@gmail.com',
            User::PASSWORD => Hash::make('password'),
            User::CODE => 'GCH18325',
            User::PHONE => '0865628160',
            User::BLIND => 0,
            User::STATUS => 1
         ]);

         $accountRole = Role::findByName(ROLES['STUDENT']);
         $account->syncRoles($accountRole);

         $account = User::create([
            User::NAME => 'Vu Duck 03',
            User::EMAIL => 'student-blind@gmail.com',
            User::PASSWORD => Hash::make('password'),
            User::CODE => 'GCH18326',
            User::PHONE => '0865628161',
            User::BLIND => 1,
            User::STATUS => 1
         ]);

         $accountRole = Role::findByName(ROLES['STUDENT']);
         $account->syncRoles($accountRole);

    }
}
