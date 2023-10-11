<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRepositoryCommand extends FileFactoryCommand
{
   
    public function setStubName() {

        return 'repository';
    }
    public function setFilePath() {

        return "App\Repository\\";
    }
    public function setSuffix() {

        return "Repository";
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create repository class';

    /**
     * Execute the console command.
     */
}
