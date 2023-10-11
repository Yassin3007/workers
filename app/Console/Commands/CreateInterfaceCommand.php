<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateInterfaceCommand extends FileFactoryCommand
{
   
    public function setStubName() {

        return 'interface';
    }
    public function setFilePath() {

        return "App\Interfaces\\";
    }
    public function setSuffix() {

        return "Interface";
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create Inerface class';

    /**
     * Execute the console command.
     */
}
