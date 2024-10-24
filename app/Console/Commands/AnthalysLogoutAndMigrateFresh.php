<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AnthalysLogoutAndMigrateFresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anthalys:pagen';
    protected $description = 'Forza il logout dell\'utente loggato e esegue migrate:fresh';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Forza il logout dell'utente se loggato
        if (Auth::check()) {
            Auth::logout();
            Session::flush(); // Elimina tutti i dati della sessione
            $this->info('Utente loggato disconnesso con successo.');
        } else {
            $this->info('Nessun utente era loggato.');
        }

        // Esegui migrate:fresh
        $this->info('Esecuzione di migrate:fresh...');
        $this->call('migrate:fresh');

        // Continua con eventuali seeder o ulteriori comandi
        $this->call('db:seed');

        $this->info('Migrazione completata con successo.');
    }
}
