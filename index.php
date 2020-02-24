<?php 
    
    // FRONT CONTROLLER


    // 1. Общие настройки
    ini_set('display_errprs', 1);
    error_reporting(E_ALL);

    // 2. Подключение файлов системы
    session_start();
    define('ROOT', dirname(__FILE__));
	
    require_once(ROOT . '/components/autoload.php');

	
    // require_once(ROOT . '/components/Router.php');
	// include_once ROOT . '/components/DB.php';
	
    // 3. Установка соединения с БД
    
    // 4. Вызов Rounter
    $obj = new Router();
    $obj->run();

?>