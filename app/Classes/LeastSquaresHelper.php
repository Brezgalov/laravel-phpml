<?php  namespace App\Classes;

use Phpml\Regression\LeastSquares;

class LeastSquaresHelper {
    public static function validateInput($input, $day, $src) {
    	$timeLength = count($input[$day][$src]['time']);
    	$loadLength = count($input[$day][$src]['load']);

    	$result = ($timeLength == $loadLength) && ($loadLength > 1);

    	return $result;
    }

    public static function getPredictCoords($input, $day, $src, $left, $right, $step) {
    	$results = [];
    	$time = $input[$day][$src]['time'];

    	$samples = [];//X
        $targets = $input[$day][$src]['load'];//Y

        if (!LeastSquaresHelper::validateInput($input, $day, $src)) {
        	return $results;
        }

        for ($i = 0; $i < count($time); $i++) {
        	$samples[$i] = [$time[$i]];
        }

        $regression = new LeastSquares();
        //dd($samples, $targets);
        $regression->train($samples, $targets);
        
        for ($x=$left; $x <= $right; $x+=$step) {
        	$predict = $regression->predict([$x]);
        	array_push($results, [
        		'x' => $x,
        		'y' => $predict
    		]);
        }

        return $results;        
    }

    public static function getPredictCoordsBySections($input, $day, $src, $left, $right, $step, $sections) {
    	$result = [
    	];

    	$sectionSize = ($right - $left) / $sections;



    	return $result;
    }
}