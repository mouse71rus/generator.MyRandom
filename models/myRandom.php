<?php
	/**
	* Класс Random
	*
	* @author mouse71rus
	* @version 1.0.0
	* @copyright Copyright (c) 2019, mouse71rus
	*/
	class myRandom
	{
		public $seed;

		private $multiplier;//a
		private $addend;//c
		private $mask;//m

        public function __construct($seed = false)
        {
        	if($seed)
        	{
				$this->seed = $seed;
        	}
        	else
            {
            	$this->seed = time();
            }

            $this->multiplier = 22695477;
			$this->addend = 1;
			$this->mask = 4294967296;
        }

        public function __construct2($seed)
        {
            $this->seed = $seed;

            base::__construct();

            /*$this->$multiplier = 22695477;
			$this->$addend = 1;
			$this->$mask = PHP_INT_MAX;*/
        }

        public function rand()
        {
        	$this->seed = ($this->multiplier * $this->seed + $this->addend) % $this->mask;

        	return $this->seed;
        }


        public function randRange($min, $max)
        {
        	return ($min + 1) + rand() % $max;
        }

        public function rand_01()
        {
        	return $this->rand() % $this->mask;
        }

        public function FibGenerate($LagA, $LabB, $Count)
        {
        	
        	
        	return $this->rand() % $this->mask;
        }
	}

?>