<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Classes\LeastSquaresHelper;
use App\Classes\LeastSquaresModel;

class ChartController extends Controller
{
    public function getChart() {
    	$data = Input::all();

        $day = $data['day'];
        $src = $data['src'];
        $length = $data['length'];

    	//@TODO: remove hardcode
    	$predictInputData = $this->getPredictInputFile('/json/test-log-1440.json');
        //dd($predictInputData);
    	$inputCoords = $this->getCoordsFromInput($predictInputData, $day, 'sources');
    	//$predictCoords = LeastSquaresHelper::getPredictCoords($predictInputData, $day, 'sources', 0, 1440, 1);

        $test = new LeastSquaresModel($predictInputData, $length);
        $test->train('Saturday', 'sources', 0, 1440, 1, true);
        $predictCoords = $test->getOutput();

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
}
