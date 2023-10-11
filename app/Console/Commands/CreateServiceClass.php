<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServiceClass extends FileFactoryCommand
{
   
    
    public function setStubName() {

        return 'service';
    }
    public function setFilePath() {

        return "App\Services\\";
    }
    public function setSuffix() {

        return "Service";
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create service class';

    /**
     * Execute the console command.
     */
    
}
