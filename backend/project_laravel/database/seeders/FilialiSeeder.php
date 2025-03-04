<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilialiSeeder extends Seeder
{

    public function getFiliali():array
    {
        return
        [
            [
                'id'         => 1,
                'codice'     => "892892-FAFNAF",
                'indirizzo'  => "via dei bovini n1",
                'città'      => "Lizzano",
                'cap'        => "74025",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id'        => 2,
                'codice'    => "124030-POLIAS",
                'indirizzo' => "via Santo Antonio n45b",
                'città'     => "Manduria",
                'cap'       => "74024",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('filiale')->insertOrIgnore(
            self::getFiliali()
        );
    }
}
