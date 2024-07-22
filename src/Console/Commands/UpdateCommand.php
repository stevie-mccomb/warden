<?php

namespace Stevie\Warden\Console\Commands;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warden:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update your Warden database tables with the values in your Warden config file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->runSeeders();

        $this->line('Finished.');
        $this->newLine();
    }

    /**
     * Run the database seeders.
     */
    private function runSeeders(): void
    {
        $this->line('Running seeders...');

        $this->line('Seeding capabilities table...');
        (new (config('warden.class_map.seeders.capabilities')))->run();

        $this->line('Seeding roles table...');
        (new (config('warden.class_map.seeders.roles')))->run();

        $this->line('Seeding capability_capability table...');
        (new (config('warden.class_map.seeders.capability_capability')))->run();

        $this->line('Seeding capability_role table...');
        (new (config('warden.class_map.seeders.capability_role')))->run();
    }
}
