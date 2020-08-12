<?php

namespace Zijinghua\Zfilesystem\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Zijinghua\Zfilesystem\ZServiceProvider;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'zfilesystem:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this package init';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publish config to config cache');
        $this->call('vendor:publish', ['--provider' => ZServiceProvider::class, '--tag' => ['config']]);
        $this->info('Migrating the database tables into your application');
        $this->call('migrate');
    }
}
