<?php
	/**
	* Класс, предназначенный для вывода ошибок
	* 
	* @author mouse71rus
	* @version 1.0.0
	* @copyright Copyright (c) 2019, mouse71rus
	*/
	class ErrorsController 
	{
		/**
		* Выводит страницу (404)NOT FOUND
		*/
		public function actionE404()
		{
			header("HTTP/1.0 404 Not Found");

            require(ROOT . '/views/errors/404.php');
			
			return true;
		}

		
	}

?>