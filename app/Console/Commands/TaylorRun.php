<?php

namespace App\Console\Commands;

use App\Helpers\IRC\Bot\Client\Client;
use Illuminate\Console\Command;

class TaylorRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tay:run {server=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes Taylor do a bit of running...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $irc = new Client($this->argument('server'));
    }
}
