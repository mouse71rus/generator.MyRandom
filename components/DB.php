<?php
/**
* Класс для работы с БД
* 
* @author mouse71rus
* @version 1.0.0
* @copyright Copyright (c) 2019, mouse71rus
*/
class DB
{	
	/**
	* Устанавливает соединение с БД.
	* 
	* @return PDO
	*/
	public static function getConnection()
	{
		$paramsPath = ROOT . '/config/db_params.php';//Параметры БД хранятся в этом файле
		$params = include($paramsPath);

		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        	PDO::ATTR_EMULATE_PREPARES   => false
		];

		$dsn = "mysql:host={$params['host']};dbname={$params['dbname']};charset={$params['charset']}";
		$db = new PDO($dsn, $params['user'], $params['password'], $opt);
		
		return $db;
	}
}

?>