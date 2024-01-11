<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Helpers\IRC\Server\Nickserv;

class CheckLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dh:check-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks login w/ Darkscience';

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
    public function handle()
    {
        $this->line('Grabbing credentials...');

        $user = $this->ask('Username?', 'linky');
        $pass = $this->secret('Password?');

        $this->line('Attempting authentication with creds given...');

        $response = with(new Nickserv())
            ->login($user, $pass);
        dd($response);
    }
}
