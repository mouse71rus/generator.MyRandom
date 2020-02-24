<?php
	class App 
	{
		/**
		* Технический метод. Помогает выводить массив в читаемом формате
		*/
		public static function show($obj)
		{
			echo "<pre>";
			print_r($obj);
			echo "</pre>";
			return;
		}

	}

?>