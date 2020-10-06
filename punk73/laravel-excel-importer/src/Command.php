<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Console\Command as BasedCommand;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
// use Maatwebsite\Excel\Facade\Excel

class Command extends GeneratorCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:importer {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 
        'Command to create importer for specific model and scaffold the excel format';

    public function getModel(){
        $model = $this->argument('model');


        $m = explode('\\', $model);

        return $m[count($m) - 1];
    }

    protected function getStub(){
        
        $stub = $stub ?? '/stubs/import.stub';

        return __DIR__ . $stub;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') || ! $this->option('force')) && $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        $this->info($this->type.' created successfully.');
    }
}