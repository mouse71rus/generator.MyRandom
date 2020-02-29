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

        public function setAdvancedSetting($multiplier = 22695477, $addend = 1, $mask = 4294967296)
        {
        	$this->multiplier = $multiplier;
			$this->addend = $addend;
			$this->mask = $mask;
        }

        public function rand()
        {
        	$this->seed = ($this->multiplier * $this->seed + $this->addend) % $this->mask;
        	
        	return $this->seed;
        }


        public function randRange($min, $max)
        {
        	return ($min) + $this->rand() % $max;
        }

        public function rand_01()
        {
        	$a = $this->rand();
        	return ($a / $this->mask) - intval($a / $this->mask);
        }

        public function FibGenerate($LagA, $LagB, $Count)
        {
        	if ($Count == 0) 
        	{
        		return array();
        	}


        	$arr = array();
        	$max = $this->max($LagA, $LagB);
        	for ($i = 0; $i < $max; $i++)
    		{
    			$arr[$i] = $this->rand_01();
    		}
			$mas = array();
        	for ($i = 0; $i < $Count; $i++)
    		{
    			if ($arr[$max - $LagA] >= $arr[$max - $LagB])
    			{
    				$mas[$i] = $arr[$max - $LagA] - $arr[$max - $LagB];
    			}
    			else
    			{
    				$mas[$i] = $arr[$max - $LagB] - $arr[$max - $LagA];
    			}

    			for ($j = 0; $j < $max; $j++)
    			{
    				$arr[$j] = $this->rand_01();
    			}
    		}

        	return $mas;
        }

        private function max($A, $B)
        {
        	return ($A > $B) ? $A : $B;
        }

        public function getPI($count)
        {
        	$kol = 0;
        	for ($i = 0; $i < $count; $i++)
    		{
    			$x = $this->rand_01();
    			$y = $this->rand_01();

    			if($this->insideCircle($x, $y))
    				$kol++;
    		}

    		return 4 * ($kol / $count);
        }

        private function insideCircle($x, $y)
        {
        	$radius = 0.5;
        	$circle_X = 0.5;
        	$circle_Y = 0.5;

        	return $radius > sqrt((($circle_X - $x) ** 2) + (($circle_Y - $y) ** 2));
        }
	}

?>