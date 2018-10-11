<?php

/*
 *  Copyright © All Rights Reserved by Vizrex (Private) Limited 
 *  Usage or redistribution of this code is strictly prohibited
 *  without written consent of Vizrex (Private) Limited.
 *  Queries are welcomed at copyright@vizrex.com
 */

/**
 * Description of LaravizServiceProvider
 *
 * @author Zeshan
 */

namespace Vizrex\LaravelEnvSwitcher;

use Vizrex\Laraviz\BaseServiceProvider;

class LaravelEnvSwitcherProvider extends BaseServiceProvider
{
    public function register(){}
    
    public function boot()
    {
        //dd(__DIR__."/../resources/lang");
        // Commands
        $this->commands(['Vizrex\LaravelEnvSwitcher\Console\Commands\SwitchEnv']);
        
        $this->loadTranslationsFrom(__DIR__."/../resources/lang", self::getNamespace());
        
    }
}
