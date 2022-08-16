<?php

namespace Gnumarquez\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Whatsapp506 resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Instalando Gnumarquez/Whatsapp...');
        $this->callSilent('vendor:publish', ['--provider'=>'Gnumarquez\WaServiceProvider']);
        $this->callSilent('migrate');
        $this->info('Whatsapp506 instalado correctamente.');
    }


  
}
