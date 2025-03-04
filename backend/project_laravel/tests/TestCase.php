<?php

namespace Tests;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Preparare i dati di test prima dell'esecuzione del test.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Svuoto il DB di test
        self::tableReset();

        // Esegui le migrazioni e i seeder per ricreare il db
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    /**
     * Funzione che svuota il database di test 
     * @return void
     */
    public function tableReset(): void
    {
        // Disattivo i controlli sulle chiavi esterne
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Prendo tutte le tabelle del DB di test
        $tables = DB::select('SHOW TABLES');

        // Smolecolo le tabelle
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_" . env('DB_DATABASE')}; 
            DB::statement('DROP TABLE IF EXISTS ' . $tableName);
        }

        // Riabilito i controlli sulle chiavi esterne
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
