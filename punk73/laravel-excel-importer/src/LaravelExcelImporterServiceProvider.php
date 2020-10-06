<?php

namespace punk73\LaravelExcelImporter;

use punk73\LaravelExcelImporter\ExportCommand;
use punk73\LaravelExcelImporter\ImportCommand;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use punk73\LaravelExcelImporter\Command;
;

class LaravelExcelImporterServiceProvider extends ServiceProvider {
    
    public function boot(){
        $this->publishes([
            $this->getConfigPath() => config_path('laravel_excel_importer.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Command::class,
                ExportCommand::class,
                ImportCommand::class
            ]);
        }
    }


    public function register(){
        $this->app->register("Maatwebsite\Excel\ExcelServiceProvider");
    }

    protected function getConfigPath(){
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'laravel_excel_importer.php';
    }
}