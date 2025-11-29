<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class migrateStartApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-start-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);

        $this->call('passport:client', [
            '--personal' => true,
        ]);
        $this->info('App migration and setup completed successfully!');
    }
}
