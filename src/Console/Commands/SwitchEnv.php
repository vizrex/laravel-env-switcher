<?php

/*
 *  Copyright © All Rights Reserved by Vizrex (Private) Limited 
 *  Usage or redistribution of this code is strictly prohibited
 *  without written consent of Vizrex (Private) Limited.
 *  Queries are welcomed at copyright@vizrex.com
 */

namespace Vizrex\LaravelEnvSwitcher\Console\Commands;

use Vizrex\Laraviz\Console\Commands\BaseCommand;

/**
 * Description of SwitchEnv
 *
 * @author Zeshan
 */
class SwitchEnv  extends BaseCommand
{
    const ENV_DEV = "dev";
    const ENV_PROD = "prod";
    const ENV_TEST = "testing";
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:switch {new_env} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Switch Application Modes e.g. dev, prod or testing";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->description = $this->str("description");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newEnv = $this->getNewEnvironment();
        $activeEnv = $this->getActiveEnvironment();
        
        $this->debug($this->str("new_env").":\t".$newEnv);
        $this->debug($this->str("active_env").":\t".$activeEnv);
        
        // Check if target environment is already activated
        if($newEnv == $activeEnv)
        {
            $this->info($this->str("no_action", ["target" => $newEnv]));
        }
        else if($this->option("force") || 
            $this->ask($this->str("confirmation", ["target" => $newEnv]), 'n') == 'y')
        {
            $this->switchToEnvironment($newEnv, $activeEnv);
            $this->info($this->str("success", ["target" => $newEnv]));
        }
    }
    
    private function switchToEnvironment($newEnv, $activeEnv)
    {        
        $envFile = $this->getEnvFilePath();
        $newEnvFile = $this->getEnvFilePath($newEnv);
        
        if($activeEnv !== null)
        {
            $this->updateActiveEnvFile($envFile, $activeEnv);
        }
        
        if(!file_exists($newEnvFile))
        {
            $this->debug($this->str("no_exists_creating", ["target" => $newEnvFile]));
            copy($this->getEnvFilePath("example"), $newEnvFile);
        }
        
        $this->activateNewEnvFile($envFile, $newEnv);
    }
    
    private function updateActiveEnvFile($envFile, $activeEnv)
    {
        $activeEnvFile = $this->getEnvFilePath($activeEnv, true);
        $originalNameOfActiveEnvFile = $this->getEnvFilePath($activeEnv);
        
        $this->debug($this->str("copying", ["source" => $envFile, "destination" => $activeEnvFile]));
        copy($envFile, $activeEnvFile);
        
        $this->debug($this->str("renaming", ["source" => $activeEnvFile, "destination" => $originalNameOfActiveEnvFile]));
        rename($activeEnvFile, $originalNameOfActiveEnvFile);
    }
    
    private function activateNewEnvFile($envFile, $newEnv)
    {
        $newEnvFileName = $this->getEnvFilePath($newEnv);
        $newActiveEnvFileName = $this->getEnvFilePath($newEnv, true);
        
        $this->debug($this->str("copying", ["source" => $newEnvFileName, "destination" => $envFile]));
        copy($newEnvFileName, $envFile);
        
        $this->debug($this->str("renaming", ["source" => $newEnvFileName, "destination" => $newActiveEnvFileName]));
        rename($newEnvFileName, $newActiveEnvFileName);
    }
    
    private function getEnvFilePath($env = null, $isActive = false)
    {
        $envFilePath = getcwd().'/.env';
        if($env !== null)
        {
            $envFilePath = "$envFilePath.$env";
            if($isActive)
            {
                $envFilePath = "$envFilePath.active";
            }
        }
        
        return $envFilePath;
    }
    
    private function getActiveEnvironment()
    {
        $validEnv = $this->getValidEnvironments();
        foreach($validEnv as $env)
        {
            if(file_exists(getcwd().'/.env.'.$env.".active"))
                return $env;
        }
        
        return null;
    }
    
    private function getValidEnvironments()
    {
        return [self::ENV_DEV, self::ENV_PROD, self::ENV_TEST];
    }
    
    private function getNewEnvironment()
    {
        $newEnv = $this->argument("new_env");
        if(!in_array($newEnv, $this->getValidEnvironments()))
        {
            $this->error($this->str("invalid", ["target" => $newEnv]));
            abort(-1);
        }
        
        return strtolower($newEnv);
    }

    protected function setNamespace()
    {
        $this->namespace = \Vizrex\LaravelEnvSwitcher\LaravelEnvSwitcherProvider::getNamespace();
    }

}
