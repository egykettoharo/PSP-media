<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
class Reset extends Command
{
    protected $signature = 'reset';

    protected $description = 'Reset the database';


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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $tables = [];
        foreach (DB::select('SHOW TABLES') as $k => $v) {
            $tables[] = array_values( (array)$v )[0];
        }
        foreach($tables as $table) {
            Schema::drop($table);
        }
        Artisan::call('migrate:install');
        print Artisan::output();
        Artisan::call('migrate');
        print Artisan::output();
        echo 'Migration completed.' . PHP_EOL;
    }
}