<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

abstract class FileFactoryCommand extends Command
{
    protected $file ; 
    public function __construct(Filesystem $file){

        parent::__construct();
        $this->file = $file ;
    }

    abstract function setStubName() ;
    abstract function setFilePath() ;
    abstract function setSuffix() ;
    public function singleClassName($name){

        return ucwords(Pluralizer::singular($name));
    }

    public function stubPath(){
        $stubName = $this->setStubName();
        return __DIR__.'\..\..\..\stubs\\'. $stubName . '.stub';
    }
    

    public function stubVariables(){
        return [
            'name'=>$this->singleClassName($this->argument('classname'))
        ];
    }

    public function stubContent($stubPath,$stubVariables){
        $content = file_get_contents($stubPath);
        foreach($stubVariables as $search =>$name){
            $contents = str_replace('$'.$search,$name ,$content);
        }
        return $contents ; 

    }
    public function makeDir($path){

        $this->file->makeDirectory($path , 0777,true , true);
        return $path ;
    }

    public function getPath(){
        $filePath = $this->setFilePath();
        $suffix = $this->setSuffix();
        return base_path($filePath).$this->singleClassName($this->argument('classname')). "{$suffix}.php" ;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  
    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $path = $this->getPath();
        $this->makeDir(dirname($path));
        
        if($this->file->exists($path)){
            $this->info('this file already exists');
        }
        else{

            $content = $this->stubContent($this->stubPath(),$this->stubVariables());
            $this->file->put($path,$content) ;
            $this->info('this file has been created successfully ');
        }
        

       
    }
}
