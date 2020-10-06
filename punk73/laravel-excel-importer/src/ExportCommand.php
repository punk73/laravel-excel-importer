<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Console\Command;

class ExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export {modelname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to export laravel model to excel file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //make sure model exists

        // run Excel::export 

        // notify user where the file is;
    }
}
