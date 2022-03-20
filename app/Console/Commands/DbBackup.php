<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Database Backup';


    protected string $dir = 'visapickmap.com';
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
     * @return int
     */
    public function handle()
    {
        if (!Storage::disk('backupLocal')->exists('/backups')) {
            Storage::disk('backupLocal')->makeDirectory('/backups');
        }
        $date = Carbon::now()->setTimezone('Asia/Tehran')->format('Y-m-d-H:i');
        $filenameDatabase = "backup-database-" . $date . ".zip";
        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path().'/backups/' . $filenameDatabase;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);
        $file_local = Storage::disk('backupLocal')->get('/backups/' . $filenameDatabase);
        Storage::disk('backupFtp')->put('/'.$this->dir.'/' . $filenameDatabase, $file_local);
        Storage::disk('backupLocal')->delete('/backups/' .$filenameDatabase);
        $this->info('The Database Backup is Complete');
    }
}
