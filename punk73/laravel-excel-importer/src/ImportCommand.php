<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import {modelname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import excel file to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function isClassExist($modelname){
        
        return class_exists($modelname, false);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //make sure model exist
        $modelname = trim( $this->argument('modelname') );
        $isClassExist = $this->isClassExist($modelname);

        $res = compact('modelname', 'isClassExist');
        // make sure file to import exist

        // check if we already has the ExcelImport class;

        // if not, create 

        // run Excel::import

        // notify user the progress
        $this->info("hey, it worked!". json_encode($res));

    }
}
