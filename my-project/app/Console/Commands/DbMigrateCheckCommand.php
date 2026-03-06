<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DbMigrateCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check
                            {action? : status|rollback|fresh - Action à exécuter}
                            {--seed : Exécuter db:seed après migrate:fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie le statut des migrations et exécute status, rollback ou migrate:fresh';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action') ?? 'status';

        match ($action) {
            'status' => $this->runStatus(),
            'rollback' => $this->runRollback(),
            'fresh' => $this->runFresh(),
            default => $this->runStatus(),
        };

        return Command::SUCCESS;
    }

    protected function runStatus(): void
    {
        $this->info('Exécution de php artisan migrate:status');
        $this->newLine();
        Artisan::call('migrate:status');
        $this->line(Artisan::output());
    }

    protected function runRollback(): void
    {
        if (!$this->confirm('Voulez-vous vraiment exécuter migrate:rollback ?', false)) {
            $this->warn('Annulé.');
            return;
        }
        $this->info('Exécution de php artisan migrate:rollback');
        Artisan::call('migrate:rollback');
        $this->line(Artisan::output());
    }

    protected function runFresh(): void
    {
        if (!$this->confirm('ATTENTION : migrate:fresh supprime TOUTES les données. Continuer ?', false)) {
            $this->warn('Annulé.');
            return;
        }
        $this->info('Exécution de php artisan migrate:fresh');
        Artisan::call('migrate:fresh');
        $this->line(Artisan::output());

        if ($this->option('seed')) {
            $this->info('Exécution de php artisan db:seed');
            Artisan::call('db:seed');
            $this->line(Artisan::output());
        }
    }
}
