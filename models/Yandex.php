<?php
    /**
    * Класс Yandex - модель для работы с Яндексом
    *
    * @author mouse71rus
    * @version 1.0
    * @copyright Copyright (c) 2019, mouse71rus
    */
	class Yandex 
	{
        /**
        * Возвращает ID приложения, зарегистрированного на Яндексе
        *
        * @return string
        */
		public static function getApplicationID()
		{
            $confPath = ROOT . '/config/yandex/yandex.php';
            $config = include($confPath);
			return $config['ID'];
		}

        /**
        * Возвращает Password приложения, зарегистрированного на Яндексе
        *
        * @return string
        */
        public static function getApplicationPassword()
        {
            $confPath = ROOT . '/config/yandex/yandex.php';
            $config = include($confPath);
            return $config['Password'];
        }

        /**
        * Проверяет ответ на ошибки в запросе
        *
        * @param array $data - массив с данными
        *
        * @return bool
        */
        public static function checkErrorResponce($data)
        {
            return isset($data['errors']) || isset($data['error']);
        }

        /**
        * Сохраняет указанный токен в БД
        *
        * @param integer $userID - ID пользователя
        * @param string $token - токен
        * @param string $refresh_token - refresh токен
        * @param string $expires - время жизни токена в секундах
        * @param string $token_type - тип токена
        * @param string $device_id - UUID устройства
        *
        * @return bool
        */
		public static function saveToken($token, $token_type, $expires)
		{
            $responce = self::getInformationAccountByToken($token);

            if (self::checkErrorResponce($responce))//Лучше конечно ошибки вернуть
            {
                return $responce;
            }


            $email = $responce['default_email'];
            $firstName = $responce['first_name'];
            $lastName = $responce['last_name'];
            $yandexID = $responce['id'];

            $user = User::getUserByEmail($email);

            if($user)
            {
                //update
                $result = User::bindYandexAccount($user['ID'], $token, $expires, $token_type, $yandexID);
                if($result)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                //create
                $res = User::register_yandex($firstName, $lastName, $email, $token, $expires, $token_type, $yandexID);
                if ($res) 
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
			
            return true;
		}


        /**
        * Возвращает личную информацию об аккаунте
        *
        * @param string $oAuthToken - токен
        *
        * @return array
        */
        public static function getInformationAccountByToken($oAuthToken)
        {
            $url = 'https://login.yandex.ru/info';
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: OAuth ' . $oAuthToken]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвращает веб-страницу

            $result = curl_exec($ch);
            
            curl_close($ch);

            $info = json_decode($result, true);

            if(empty($info))
            {
                return array(
                    'errors' => array(
                        array(
                            'error_type' => "noData", 
                            'message' => "Нет информации об указанном Яндекс Аккаунте, возможно доступ запрещён", 
                            'location' => "auth-api-->Yandex-->getInformationAccountByToken"), ));
            }
            return $info;
        }
	}
?>