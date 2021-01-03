<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use PDO;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
class MasterImport implements WithHeadingRow, ToCollection, WithProgressBar
{
    use Importable;

    protected $model;
    protected $cols;
    protected $mappingResult;
    protected $skip = [
        "id", "created_at", "updated_at"
    ];

    public function collection(Collection $rows)
    {

        foreach ($rows as $key => $row) {
            # code...
            $this->model($row->toArray());
        }
    }
    
    public function model(array $row)
    {
        # insert data to model
        $data = [];
        foreach ($this->cols as $col) {
            # code...
            if(in_array($col, $this->skip)) {
                continue;
            }

            if(isset($row[$col])) {
                $value = $row[$col];
                $data[$col] = $value;
            }
        }
        
        return $newData = (new $this->model)->firstOrCreate($data);

    }

    public function getCols(){
        return $this->cols;
    }

    private function initCols() {
        // get table column list;
        $table = (new $this->model)->getTable();
        $cols = Schema::getColumnListing($table);
        $this->cols = $cols;
    }

    public function findCoordinate($col, Worksheet $sheet) {
        $headerIndex = config('HEADER_INDEX', 1);
        $highestColName = $sheet->getHighestColumn($headerIndex);
        $highestCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColName);
        if(isset( $this->coordinateHashMap[$col])) {
            return  $this->coordinateHashMap[$col];
        }

        $hashMap = [];
        for ($i=1; $i <= $highestCol; $i++) { 
            # code...
            $cell = $sheet->getCellByColumnAndRow($i, $headerIndex );
            $val = $cell->getValue();
            $coordinate = $cell->getCoordinate();
            $hashMap[$val] = $coordinate;
        }

        $this->coordinateHashMap = $hashMap;

        return isset($hashMap[$col]) ? $hashMap[$col] : null; 
    }
    
    
    public function initMappingResult() {
        // get table column list;
        $filename = $this->filename;
        $filepath = Storage::path($filename);
        $spreadsheet =  IOFactory::load($filepath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestCol = $sheet->getHighestDataColumn(1);
        // read the file ;
        
        // get coordinate for every cols
        // contoh : name => "A1", id => "A4", dst
        $mappingResult = [];
        foreach ($this->cols as $col) {
            # code...
            if(in_array($col, $this->skip)) {
                continue;
            }

            $coordinate = $this->findCoordinate($col, $sheet);
            // kalau column name tidak ketemu di file excel, skip;
            if($coordinate == null) {
                continue;
            }
            $mappingResult[$col] = $coordinate;
        }
        $this->mappingResult = $mappingResult;

    }

    public function getMappingResult(){
        return $this->mappingResult;
    }

    private function initFilename() {
        //"-import.xlsx"; //default nya xlsx, nanti kita dinamis kan.
        $table = (new $this->model)->getTable();
        $this->filename = $table . config("IMPORT_FILE_SUFFIX", "-import") .".". config("EXCEL_FILETYPE", 'xlsx'); 
        
    }
    public function __construct($model) {
        $this->model = $model;
        $this->initCols();
        $this->initFilename();
    }

    
}