<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeCustom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve:custom {host=0.0.0.0} {port=9000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on a custom host and port';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $host = $this->argument('host');
        $port = $this->argument('port');

        // Запускаем сервер с помощью Symfony Process
        $process = new Process(['php', 'artisan', 'serve', "--host={$host}", "--port={$port}"]);
		$process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return 0;
    }
}
