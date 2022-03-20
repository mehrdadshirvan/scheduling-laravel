<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class InsertQ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert post every 1 min';


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
        try {
            DB::table('posts')->insert(['title'=> time()]);
        }catch (\Exception $e){
            DB::table('posts')->insert(['title'=>'error']);
        }

//        Post::query()->create(['title'=>time()]);
    }
}
