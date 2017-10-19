<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phpml\Regression\LeastSquares;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $samples = [[1], [2], [3], [4], [5]];
        $targets = [5, 10, 15, 20, 25];

        $regression = new LeastSquares();
        $regression->train($samples, $targets);
        dd($regression->predict([6]));
    }
}
