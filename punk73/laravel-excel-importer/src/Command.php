<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Console\Command as BasedCommand;
use Illuminate\Support\Facades\Artisan;
// use Maatwebsite\Excel\Facade\Excel

class Command extends BasedCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:importer {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 
        'Command to create importer for specific model and scaffold the excel format';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getModel(){
        $model = $this->argument('model');


        $m = explode('\\', $model);

        return $m[count($m) - 1];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->getModel();
        // scalfold the maatwebsite/import class;
        // make it using collection 

        // scalfold the excel file
        $this->info("scalfolding the excel format for {$model} model !!!");
        // Artisan::call("make:import")

    }
}