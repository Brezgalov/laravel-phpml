<?php  namespace App\Classes;

use Phpml\Regression\LeastSquares;

class LeastSquaresModel {
	protected $input;
	protected $output;
	protected $predict;
	protected $sectionSize;

	public function __construct($input, $sectionSize) {
		// if (!LeastSquaresHelper::validateInput($input, $day, $src)) {
  //       	throw new Exception("Validation Error");
  //       }
        if ($sectionSize >= 5) {
        	$this->sectionSize = $sectionSize;
        }
		$this->input = $input;
	}

	public function train($day, $src, $left, $right, $step=1, $verbose=false) {
		$inputTime = array_slice($this->input[$day][$src]['time'], $left, $right);
		$inputLoad = array_slice($this->input[$day][$src]['load'], $left, $right);

		$chunksTime = [];
		$chunksLoad = [];
		$this->predict = [];
		if ($verbose) {
			$this->output = [];
		}

		if ($this->sectionSize > 0) {
			$chunksTime = array_chunk(
				$inputTime, 
				$this->sectionSize
			);
			$chunksLoad = array_chunk(
				$inputLoad, 
				$this->sectionSize
			);
		} else {
			$chunksTime = [ $inputTime ];
			$chunksLoad = [ $inputLoad ];
		}

		$sections = count($chunksTime);
		$sectionSize = count($chunksTime[0]);

		for ($i = 0; $i < $sections; $i++) {
			$timeChunk = $chunksTime[$i];
			$loadChunk = $chunksLoad[$i];

			$chunkSize = count($timeChunk);

			//label for predict
			if (!isset($timeChunk[$chunkSize-1])) {
				dd(
					$chunkSize,
					$timeChunk
				);
			}
			$label = 't'.$timeChunk[0].'t'.$timeChunk[$chunkSize-1].'t';
			
			//prepare params
			$timeParams = [];
			for ($j = 0; $j < $chunkSize; $j++) {
				$timeParams[$j] = [$timeChunk[$j]];
			}

			$regression = new LeastSquares();
	        $regression->train($timeParams, $loadChunk);

	        $this->predict[$label] = $regression;

	        if ($verbose) {
	        	for ($j = 0; $j < $chunkSize; $j++) {
	        		$x = $timeChunk[$j];
	        		$y = $regression->predict([$x]);
	        		array_push(
		        		$this->output,
		        		[
		        			'x' => $x,
		        			'y' => ($y < 0)? 0 : $y,
		        		]
	        		);
	        	}
	        }
		}
	}

	public function getOutput() {
		return $this->output;
	}

	public function getPredict($x) {
		$keys = array_keys($this->predict);
		foreach ($keys as $key) {
			$split = explode('t', $key);
			$left = (int)$split[1];
			$right = (int)$split[2];

			if ($x >= $left && $x <= $right) {
				$result = $this->predict[$key]->predict([$x]);
				if ($result < 0) {
					return 0;
				} else {
					return $result;
				}
			}
		}

		return null;
	}
}