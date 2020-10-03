<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Support\ServiceProvider;
use punk73\LaravelExcelImporter\Command;

class LaravelExcelImporterServiceProvider extends ServiceProvider {
    
    public function boot(){
        // dd('hello wordls');
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         Command::class,
        //     ]);
        // }
    }


    public function register(){

    }
}