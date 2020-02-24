<?php
    /**
    * Класс маршрутизации запросов к серверу
    *
    * @author mouse71rus
    * @version 1.0.0
    * @copyright Copyright (c) 2019, mouse71rus
    */
    class Router 
    {
        /**
         * @var Допустимые маршруты
         */
        private $routes;

        public function __construct()
        {
            $routesPath = ROOT . '/config/routes.php';//Маршруты хрянятся в следующем файле
            $this->routes = include($routesPath);
        }

        /**
        * Получает URL запроса
        *
        * @return string
        */
        private function getURI()
        {
            if(!empty($_SERVER['REQUEST_URI']))
            {
                return trim($_SERVER['REQUEST_URI'], '/');
            }
        }
        
        /**
        * Запускаемый метод
        */
        public function run()
        {
            $uri = $this->getURI();
			$result = null;
            
            //Проверяет наличие такого запроса в списке допустимых маршрутов
            foreach($this->routes as $uriPattern => $path)
            {
                //Сравниваем $uriPattern и $uri
				//Если есть совпадение, определить какой Controler и action обрабатывают запрос
                if(preg_match("~^$uriPattern$~", $uri))
                {
                    //Получаем внутренний маршрут
                    $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                    //Определить, какой Controller и action обрабатывают запрос, а также параметры
                    $segments = explode('/', $internalRoute);


                    $controllerName = ucfirst(array_shift($segments)) . "Controller";
                    $actionName = "action" . ucfirst(array_shift($segments));

                    $parameters = $segments;

                    //Подключить файл класса контроллера
                    $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                    if(file_exists($controllerFile))
                    {
                        include_once($controllerFile);

                        //Создать объект, вызвать метод (т.е. action)
                        $controllerObject = new $controllerName();

                        $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                        // $result = $controllerObject->$actionName();
                    }
                  
                    if($result != null)
                    {
                        break;
                    }
                }
            }
            if($result == null)//Если такого маршрута нет в списке допустмых, то генерируем ошибку NOT FOUND 404
            {
                $obj = new ErrorsController();
                $result = $obj->actionE404();
            }          
        }
    }


?>