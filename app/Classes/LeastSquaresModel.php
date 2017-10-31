<?php  namespace App\Classes;

use Phpml\Regression\LeastSquares;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;

use MCordingley\Regression\Algorithm\LeastSquares as MCLeastSquares;
use MCordingley\Regression\Observations;
use MCordingley\Regression\Predictor\Linear;

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

	public function trainAdvanced($day, $src, $left, $right, $verbose) {
		$inputCash = array_slice($this->input[$day][$src], $left, $right);
		$this->predict = [];
		
		if ($verbose) {
			$this->output = [];
		}

		$inputChunks = [];
		if ($this->sectionSize < 5) {
			$inputChunks = [$inputCash];
		} else {
			$inputChunks = array_chunk($inputCash, $this->sectionSize, true);	
		}
		

		foreach ($inputChunks as $inputChunk) {
			$arrayX = [];
			$arrayY = [];
			foreach ($inputChunk as $time => $load) {
				foreach ($load as $value) {
					array_push($arrayX, [$time]);
					array_push($arrayY, $value);
				}
			}
			$timeKeys = array_keys($inputChunk);
			$regression = new LeastSquares();//PHPML
	        $regression->train($arrayX, $arrayY);//PHPML

			if ($verbose) {
				foreach ($timeKeys as $timeKey) {
	        		$y = $regression->predict([$timeKey]);
	        		$coords = [
	        			'x' => $timeKey,
	        			'y' => ($y < 0)? 0 : $y,
	        		];
	        		array_push(
		        		$this->output,
		        		$coords
	        		);
				}
			}

			$start = array_shift($timeKeys);
			$end = array_pop($timeKeys);
			$this->predict['t'.$start.'t'.$end.'t'] = $regression;
		}
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

			$regression = new LeastSquares();//PHPML
	        $regression->train($timeParams, $loadChunk);//PHPML

			//load observations into single model
			// $observations = new Observations();
   //      	$merger = [1.0];//fix from lib developer DONT TOUCH!
   //      	for ($j = 0; $j < $chunkSize; $j++) {
   //      		$observations->add(array_merge($merger, [$loadChunk[$j]]), $timeChunk[$j]);	
   //      	}
	        
	  //       //train model
	  //       $algorithm = new MCLeastSquares();
	  //       $coefficients = $algorithm->regress($observations);

	  //       $regression = new Linear($coefficients);
	        // $predictedOutcome = $regression->predict(array_merge($merger, [6.0]));

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