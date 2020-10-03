<?php

namespace punk73\LaravelExcelImporter;

use Illuminate\Console\Command as BasedCommand;

class Command extends BasedCommand {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:importer';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("scalfolding the excel format!!!");
    }
}