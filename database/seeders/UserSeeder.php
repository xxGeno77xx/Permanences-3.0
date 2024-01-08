<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

// ============test names==========================================
        $names=[
            'Serge',
            'Greg',
            'William',
            'Dorcas',
            'Joanita',
            'Rosalie',
            'Hermes',
            'Hercules',
            'Félicité'
         ];
//========================ADMIN=====================================
        // $superAdmin=User::firstOrCreate([
        //     "email"=>"superadministrateur@laposte.tg",
        //     'password'=>Hash::make('Laposte+2024'),
        //     'name'=>'Super_administrateur',
        //     'service_id'=> 1,
        // ]);
//========================otherServices=============================
        foreach($names as $key => $name)
        {   
            User::firstOrCreate([
                "email" => $name."@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => $name,
                'service_id' =>  mt_rand(4,5),
            ]);
        }
//========================exploitation=============================
        User::firstOrCreate([
            "email" => "Jeremie@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'KPOGNON',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Boris@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'PATAKI',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Ola@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'OLA',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Looky@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'LOOKY',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Agbessi@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'AGBESSI',
                'service_id' =>  1,
        ]);
//========================Etudes=============================

        User::firstOrCreate([
            "email" => "Daniel@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'NYADZAWO',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Adjo@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'ADJO',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Abraham@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'AHOSE',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Yamine@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'MAMAH-IDRISSOU',
                'service_id' =>  3,
        ]);

        // User::firstOrCreate([
        //     "email" => "Kevin@laposte.tg",
        //         'password' => Hash::make('Laposte+2024'),
        //         'name' => 'Kevin',
        //         'service_id' =>  3,
        // ]);
        
//========================Reseaux=============================

        User::firstOrCreate([
            "email" => "Eli@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'HLORGBEY',
                'service_id' =>  2,
        ]);

        User::firstOrCreate([
            "email" => "Sylvanus@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'DZAKPASU',
                'service_id' =>  2,
        ]);

        User::firstOrCreate([
            "email" => "Barthélémie@laposte.tg",
                'password' => Hash::make('Laposte+2024'),
                'name' => 'ABALO',
                'service_id' =>  2,
        ]);

        // User::firstOrCreate([
        //     "email" => "Euloge@laposte.tg",
        //         'password' => Hash::make('Laposte+2024'),
        //         'name' => 'ATTIOGBE',
        //         'service_id' =>  2,
        // ]);
    }
}
