<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\ActionQueue\ActionCommandScheduled;

class License extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute queued actions - Remind, approve, suspend companies';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment(PHP_EOL.ActionCommandScheduled::run().PHP_EOL);
    }
}