<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Phpml\Regression\LeastSquares;

class ChartController extends Controller
{
    public function getChart() {
    	$data = Input::all();

    	//@TODO: remove hardcode
    	$predictInputData = $this->getPredictInputFile('/json/test-log-1440.json');
    	$inputCoords = $this->getCoordsFromInput($predictInputData, 'Saturday', 'sources');
    	$predictCoords = $this->getPredictCoords($predictInputData, 'Saturday', 'sources', 0, 1440, 1);

    	return json_encode([
    		'inputCoords' => $inputCoords,
    		'predictCoords' => $predictCoords,
		]);
    }

    private function getPredictInputFile($path) {
    	return json_decode(file_get_contents(public_path().$path), true);
    }

    private function getCoordsFromInput($input, $day, $src) {
    	$coords = [];

    	foreach ($input[$day][$src]['load'] as $key => $value) {
    		array_push(
    			$coords, 
    			[
    				'x' => $input[$day][$src]['time'][$key],
    				'y' => $value,
    			]
			);
    	}

    	return $coords;
    }

    private function getPredictCoords($input, $day, $src, $left, $right, $step) {
    	$time = $input[$day][$src]['time'];

    	$samples = [];//X
        $targets = $input[$day][$src]['load'];//Y

        for ($i = 0; $i < count($time); $i++) {
        	$samples[$i] = [$time[$i]];
        }

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        $results = [];
        for ($x=$left; $x <= $right; $x+=$step) {
        	$predict = $regression->predict([$x]);
        	array_push($results, [
        		'x' => $x,
        		'y' => $predict
    		]);
        }

        return $results;        
    }
}
