<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateStaticApiConfigCommand extends Command
{
    protected $name = 'api_config:update';
    protected $signature = "api_config:update";
    protected $description = 'Update configuration file from Morrie';

    //https://192.168.150.95/api/v0.2/facilities

    public function handle()
    {
        $url = "https://192.168.150.95/api/v0.2/facilities";


        return 0;
    }
}
