<?php

namespace punk73\LaravelExcelImporter;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use punk73\LaravelExcelImporter\MasterExport;
class ExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:model {modelname}';

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

    public function getModel(){
        $model = $this->argument('modelname');

        if(class_exists($model)){
            return $model;
        }else{
            throw new Exception("model {$model} not found. make sure model exist");
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //make sure model exists
        $model = $this->getModel();

        $table = (new $model)->getTable();
        // run Excel::export
        $pk = (new $model)->getKeyName(); 
        
        $query = (new $model)->take(10)->orderBy($pk, 'desc');

        $filename =  $table . ".xlsx";
        Excel::store(new MasterExport($query), $filename);
        $path = Storage::path($filename);
        $this->info("Great!! we stored exported file on {$path}");
        // notify user where the file is;
    }
}
