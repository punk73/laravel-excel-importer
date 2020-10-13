<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ImportCommand extends Command
{
    protected $filename;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:model {modelname}';

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
        
        return class_exists($modelname);
    }

    public function createImportFile(String $filename, Array $cols) {
        $this->info($filename);
        // create file here
        $excel = new Spreadsheet;
        $sheet = $excel->getActiveSheet();
        $startIndex = 1;
        foreach ($cols as $col) {
            # code...
            $sheet->setCellValueByColumnAndRow($startIndex, 1, $col);
            $startIndex++;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, "Xlsx");
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();
        return Storage::put($filename, $content);

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelname = trim( $this->argument('modelname') );
        $this->modelname = $modelname;
        $this->filename = (new $modelname)->getTable() . config("IMPORT_FILE_SUFFIX", "-import") .".". config("EXCEL_FILETYPE", 'xlsx'); //"-import.xlsx"; //default nya xlsx, nanti kita dinamis kan.
        
        //make sure model exist
        $modelname = $this->modelname;
        $isClassExist = $this->isClassExist($modelname);
        $filename = $this->filename;
        // make sure file to import exist
        $fileExist = Storage::exists($filename);
        $path = Storage::path($filename);

        if(!$fileExist) {
            // create file or notify users ??
            $cols = (new MasterImport($modelname))->getCols();
            # create file with proper header;
            try {
                //code...
                $create = $this->createImportFile($filename, $cols);
                // notify user to input data;
                if($create){
                    $this->info("we generate the import file for you. please check the file at {$path}");
                }else {
                    $this->info('something went wrong');
                }
                return;
            } catch (\Exception $th) {
                //throw $th;
                $this->error($th->getMessage());
            }
        }
        
        // check if we already has the ExcelImport class;
        
        // run Excel::import
        $importer = new MasterImport($modelname);
        $this->output->title('Starting import');
        $importer->withOutput($this->output)->import($path);
        $this->output->success('Import successful');

    }
}
