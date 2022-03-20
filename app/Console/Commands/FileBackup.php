<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FileBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create File Backup';


    protected $dir = 'visapickmap.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        set_time_limit(0);
        parent::__construct();
    }

    protected function scheduleTimezone()
    {
        return 'Asia/Tehran';
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
        $filename = "backup-file-" . $date . ".zip";
        $path = storage_path() . "/backups/" . $filename;
        $folder1 = storage_path('app');
        $folder2 = public_path('/uploads');
        $command = "nohup zip -r " . $path . " " . $folder1 . ' ' . $folder2;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);
        $this->info('The File Backup is Complete');
        $file_local = Storage::disk('backupLocal')->readStream('/backups/' . $filename);
        Storage::disk('backupFtp')->writeStream('/' . $this->dir . '/' . $filename, $file_local);
        Storage::disk('backupLocal')->delete('/backups/' . $filename);
        $this->info('The File Backup Upload with FTP is Complete');
    }
}
