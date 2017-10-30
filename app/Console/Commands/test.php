<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

// use Phpml\Regression\LeastSquares;
// use App\Classes\LeastSquaresModel;

use MCordingley\Regression\Algorithm\LeastSquares;
use MCordingley\Regression\Observations;
use MCordingley\Regression\Predictor\Linear;

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
        // $samples = [[1], [2], [3], [4], [5]];
        // $targets = [5, 10, 15, 20, 25];

        // $regression = new LeastSquares();
        // $regression->train($samples, $targets);
        // dd($regression->predict([6]));

        // $predictInputData = json_decode(file_get_contents(public_path().'/json/test-log-1440.json'), true);
        // $test = new LeastSquaresModel($predictInputData, 10);
        // $test->train('Saturday', 'sources', 0, 1440, 1, true);
        // dd($test->getOutput()[52], $test->getPredict(52));

        // Load the data
        $observations = Observations::fromArray([[1], [1], [1], [2], [2]], [5, 10, 15, 12, 9]);

        $algorithm = new LeastSquares;
        $coefficients = $algorithm->regress($observations);

        $predictor = new Linear($coefficients);
        $predictedOutcome = $predictor->predict(array_merge([1.0], [1]));

        dd($predictedOutcome);
    }
}
