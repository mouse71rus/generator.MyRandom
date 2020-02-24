<?php
	/**
	* Класс User - модель для работы с пользователем
	*
	* @author mouse71rus
	* @version 1.0.0
	* @copyright Copyright (c) 2019, mouse71rus
	*/
	class User
	{
		public static function bindYandexAccount($userID, $token, $expires, $token_type, $yandexID)
		{
			$db = DB::getConnection();
			
			$sql = 'UPDATE users SET Token = :token, Expires = DATE_ADD(NOW(), INTERVAL :expires SECOND), TokenType = :token_type, YandexID = :yandexID WHERE ID = :userID';
			
			$result = $db->prepare($sql);
			$result->bindParam(':token', $token, PDO::PARAM_STR);
			$result->bindParam(':expires', $expires, PDO::PARAM_INT);
			$result->bindParam(':token_type', $token_type, PDO::PARAM_STR);
			$result->bindParam(':yandexID', $yandexID, PDO::PARAM_INT);
			$result->bindParam(':userID', $userID, PDO::PARAM_INT);
			$result->execute();


			return $result->rowCount();
		}

		/**
		* Аутентификация пользователя
		*
		* @param string $login - e-mail пользователя
		* @param string $password - пароль пользователя
		*
		* @return integer Возвращает ID пользователя в случае успеха
		* @return bool Возвращает false в случае неуспеха
		*/
		public static function auth($login, $password)
		{
			$db = DB::getConnection();
			
			$sql = 'SELECT * FROM users WHERE Email = :login';
			
			$result = $db->prepare($sql);
			$result->bindParam(':login', $login, PDO::PARAM_STR);
			$result->execute();


			if($result->rowCount())
			{
				$user = $result->fetch();

				if (password_verify($password, $user['Password'])) 
				{	
					return $user['ID'];
				}
			}
			
			return false;
		}

		/**
		* Проверяет авторизован пользователь или нет
		*
		* Перенаправляет пользователя на страницу входа
		*
		* @return integer ID пользователя в случае успеха 
		*/
		public static function checkLogged()
		{
			if(isset($_SESSION['user_id']))
            {
            	return $_SESSION['user_id'];
            }


            header("Location: /login");
		}

		/**
		* Сообщает пользователь гость или нет
		*
		* @return true Пользователь гость 
		* @return false Пользователь авторизован 
		*/
		public static function isGuest()
		{
			if(isset($_SESSION['user_id']))
				return false;

			return true;
		}

		/**
		* Возвращает пользователя с указанным ID
		*
		* @param integer $user_id - ID пользователя
		*
		* @return array Пользователь
		*/
		public static function getUserByID($user_id)
		{
			$db = DB::getConnection();
			
			$sql = 'SELECT * FROM users WHERE ID = :id';
			
			$result = $db->prepare($sql);
			$result->bindParam(':id', $user_id, PDO::PARAM_INT);
			$result->execute();

			return $result->fetch();
		}

		/**
		* Возвращает пользователя с указанным e-mail
		*
		* @param integer $email - e-mail пользователя
		*
		* @return array Пользователь
		*/
		public static function getUserByEmail($email)
		{
			$db = DB::getConnection();
			
			$sql = 'SELECT * FROM users WHERE email = :email';
			
			$result = $db->prepare($sql);
			$result->bindParam(':email', $email, PDO::PARAM_STR);
			$result->execute();

			if($result->rowCount())
			{
				return $result->fetch();
			}
			return false;
		}


		public static function getUserByYandexToken($token)
		{
			$db = DB::getConnection();
			
			$sql = 'SELECT * FROM users WHERE Token = :token';
			
			$result = $db->prepare($sql);
			$result->bindParam(':token', $token, PDO::PARAM_STR);
			$result->execute();

			return $result->fetch();
		}

		/**
		* Регистрация пользователя
		*
		*/
		public static function register($firstName, $lastName, $email, $password)
		{
			$hash = password_hash($password, PASSWORD_DEFAULT);//+ Шифрование пароля BCRYPT

			$db = DB::getConnection();
			
			$sql = 'INSERT INTO users (`FirstName`, `LastName`, `Password`, `Email`) VALUES (:firstName, :lastName, :pas, :email)';
			

			$result = $db->prepare($sql);
			$result->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$result->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$result->bindParam(':pas', $hash, PDO::PARAM_STR);
			$result->bindParam(':email', $email, PDO::PARAM_STR);
			
			$res = $result->execute();

			return true;
		}

		const PASSWORD_AUTH_DEFAULT = "yandex-auth";
		/**
		* Регистрация пользователя c помощью Яндекса
		*
		*/
		public static function register_yandex($firstName, $lastName, $email, $token, $expires, $tokenType, $YandexID)
		{
			$password = self::PASSWORD_AUTH_DEFAULT;//Из-за того, что пароль не шифруется, его невозможно будет подобрать. При авторизации логин-пароль пароль будет шифроваться перед сравнением. 100% безопасный способ заблокировать вход по логин-паролю

			$db = DB::getConnection();
			
			$sql = 'INSERT INTO users (`FirstName`, `LastName`, `Password`, `Email`, `Token`, `Expires`, `TokenType`, `YandexID`) VALUES (:firstName, :lastName, :password, :email, :token, DATE_ADD(NOW(), INTERVAL :expires SECOND), :tokenType, :YandexID)';
			
			$result = $db->prepare($sql);
			$result->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$result->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$result->bindParam(':password', $password, PDO::PARAM_STR);
			$result->bindParam(':email', $email, PDO::PARAM_STR);
			$result->bindParam(':token', $token, PDO::PARAM_STR);
			$result->bindParam(':expires', $expires, PDO::PARAM_INT);
			$result->bindParam(':tokenType', $tokenType, PDO::PARAM_STR);
			$result->bindParam(':YandexID', $YandexID, PDO::PARAM_INT);
			
			$result->execute();

			return $result->rowCount();
		}


		public static function updatePassword($userID, $password)
		{
			$hash = password_hash($password, PASSWORD_DEFAULT);//+ Шифрование пароля BCRYPT

			$db = DB::getConnection();

			$sql = 'UPDATE users SET Password = :password WHERE ID = :userID';

			$result = $db->prepare($sql);
			$result->bindParam(':userID', $userID, PDO::PARAM_INT);
			$result->bindParam(':password', $hash, PDO::PARAM_STR);
			
			$result->execute();

			return $result->rowCount();
		}


		/**
		* Валидация имени
		*
		* @param string $firstName - Имя
		*
		* @return bool
		*/
		private static function checkFirstName($firstName)
		{
			$pattern_Name = '/^[a-zA-Zа-яёА-ЯЁ\-]{2,25}$/u';

			if(preg_match($pattern_Name, $firstName) == true)
			{
				return true;
			}
			return "Имя указано в неверном формате";
		}

		/**
		* Валидация фамилии
		*
		* @param string $lastName - Фамилия
		*
		* @return bool
		*/
		private static function checkLastName($lastName)
		{
			$pattern_Name = '/^[a-zA-Zа-яёА-ЯЁ\-]{2,40}$/u';

			if(preg_match($pattern_Name, $lastName) == true)
			{
				return true;
			}
			return "Фамилия указана в неверном формате";
		}

		/**
		* Валидация e-mail
		*
		* @param string $email - e-mail
		*
		* @return bool
		*/
		private static function checkEmail($email)
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				if(self::checkEmailExists($email))
				{
					return "Учётная запись с таким email уже существует";
				}
				else
				{
					return true;
				}
			}
			else
			{
				return "Email указан в неверном формате";
			}
		}

		/**
		* Проверка на существование e-mail
		*
		* @param string $email - e-mail
		*
		* @return bool
		*/
		private static function checkEmailExists($email)
		{
			$db = DB::getConnection();
			
			$sql = 'SELECT COUNT(*) FROM users WHERE Email = :email';
			

			$result = $db->prepare($sql);
			$result->bindParam(':email', $email, PDO::PARAM_STR);
			$result->execute();

			if($result->fetchColumn())
				return true;
			
			return false;
		}

		/**
		* Валидация пароля
		*
		* @param string $pas1 - Пароль 1
		* @param string $pas2 - Пароль 2
		*
		* @return bool
		*/
		public static function checkPassword($pas1, $pas2)
		{
			if($pas1 == $pas2)
			{
				if(strlen($pas1) >= 6)
				{
					if(strlen($pas1) <= 255)
						return true;
					else
						return "Пароль слишком длинный";
				}
				else
				{
					return "Пароль слишком короткий";
				}
			}
			else
			{
				return "Пароли не совпадают";
			}
		}

		/**
		* Валидация данных, необходимых для регистрации
		*
		* @param string $firstName - Имя
		* @param string $lastName - Фамилия
		* @param string $email - e-mail
		* @param string $pas1 - Пароль 1
		* @param string $pas2 - Пароль 2
		*
		* @return array Массив, содержащий возможный ошибки
		*/
		public static function validate($firstName, $lastName, $email, $pas1, $pas2)
		{
			setlocale(LC_ALL, "ru_RU.UTF-8");

			$errors = array();

			$result = self::checkPassword($pas1, $pas2);
			if(!is_bool($result))
			{
				$err['message'] = $result;
				$err['target'] = "Password";
				$errors['errors'][] = $err;
			}


			$result = self::checkFirstName($firstName);
			if(!is_bool($result))
			{
				$err['message'] = $result;
				$err['target'] = "FirstName";
				$errors['errors'][] = $err;
			}

			$result = self::checkLastName($lastName);
			if(!is_bool($result))
			{
				$err['message'] = $result;
				$err['target'] = "LastName";
				$errors['errors'][] = $err;
			}


			$result = self::checkEmail($email);
			if(!is_bool($result))
			{
				$err['message'] = $result;
				$err['target'] = "Email";
				$errors['errors'][] = $err;
			}

			return $errors;
		}	
	}

?>