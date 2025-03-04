<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutomezziSeeder extends Seeder
{

    public function getAutomezzi():array
    {
        return
        [
            [
                'id'         => 1,
                'filiale_id' => 1,    //Lizzano
                'codice'     => "892892-FAFNAF",
                'targa'      => "ARGHA678",
                'marca'      => "Legea",
                'modello'    => "T-25",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id'         => 2,
                'filiale_id' => 1,    //Lizzano
                'codice'     => "893120-KALST",
                'targa'      => "FOLFA245",
                'marca'      => "Puma",
                'modello'    => "A-23s",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')

            ],
            [
                'id'         => 3,
                'filiale_id' => 2,    //Manduria
                'codice'     => "935462-NCXIO",
                'targa'      => "QWERT298",
                'marca'      => "Legea",
                'modello'    => "T-26",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id'         => 4,
                'filiale_id' => 2,     //Manduria
                'codice'     => "356201-DAGSUX",
                'targa'      => "LKOET333",
                'marca'      => "Kium",
                'modello'    => "S5",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('automezzo')->insertOrIgnore(
            self::getAutomezzi()
        );
    }
}
