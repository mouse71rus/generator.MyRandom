<?php
	/**
	* Контроллер UserController
	*
	* Пользователь
	* 
	* @author mouse71rus
	* @version 1.0.0
	* @copyright Copyright (c) 2019, mouse71rus
	*/
	class UserController 
	{
		/**
		* Вывод страницы регистрации
		* 
		*/
		public function actionRegister()
		{
			if(isset($_SESSION['user_id']))
            {
            	header("Location: /");
            }
			$result = false;

			$firstName = "";
			$lastName = "";
			
			$email = "";
			
			$pas1 = "";
			$pas2 = "";
			
			if(isset($_POST['btn_ok']))
			{
				$firstName = $_POST['FirstName'];
				$lastName = $_POST['LastName'];
				
				$email = $_POST['Email'];
				
				$pas1 = $_POST['pas1'];
				$pas2 = $_POST['pas2'];
				
				
				$errors = array();

				$errors = User::validate($firstName, $lastName, $email, $pas1, $pas2);

				if(empty($errors))
				{
					$result = User::register($firstName, $lastName, $email, $pas1);
				}
			}

			require_once(ROOT . '/views/user/sign-up.php');
			
			return true;
		}

		/**
		* Вывод страницы изменения пароля
		*/
		public function actionEditPassword()
		{
			$userID = User::checkLogged();

			$user = User::getUserByID($userID);

			if($user['Password'] != User::PASSWORD_AUTH_DEFAULT)
			{
				return null;
			}

			$result = false;

			if(isset($_POST['Update']))
			{
				$pas1 = $_POST['pas1'];				
				$pas2 = $_POST['pas2'];				
				
				$errors = array();

				# Валидация пароля
				$res = User::checkPassword($pas1, $pas2);
				if(!is_bool($res))
					$errors['password'] = $res;
				# ^^^^^^^^^^^

				if(empty($errors))
				{
					$result = User::updatePassword($userID, $pas1);
				}
			}

			require_once(ROOT . '/views/user/editPassword.php');
			
			return true;
		}


		/**
		* Ajax-запрос авторизации с помощью Яндекс
		*/
		public function actionLogin_yandex()
		{
			if(isset($_SESSION['user_id']))
            {
            	return null;
            }

            $token = $_POST['token'];
            $token_type = $_POST['token_type'];
            $expires = $_POST['expires'];


            $user = User::getUserByYandexToken($token);

            if ($user) 
            {
            	//auth
            	$_SESSION["user_id"] = $user['ID'];
            	echo json_encode(array("status" => "ok"));
            }
            else
            {
            	//register
            	$result = Yandex::saveToken($token, $token_type, $expires);
            	if(Yandex::checkErrorResponce($result))
            	{
					echo json_encode($result);
            	}
            	else
            	{
            		if ($result) 
            		{
            			$user = User::getUserByYandexToken($token);
            			$_SESSION["user_id"] = $user['ID'];
            			echo json_encode(array("status" => "ok"));
            		}
            		else
					{
	            		$errors["errors"][]['message'] = "Не удалось войти. Повторите попытку позже.";
						echo json_encode($result);
            		}
            	}
            	
            }
			
			return true;
		}



		public function actionLogin()
		{
			if(isset($_SESSION['user_id']))
            {
            	header("Location: /");
            }
            $YandexAppID = Yandex::getApplicationID();// Необходим, чтобы сформировать ссылку для получения новых Токенов

			$result = false;

			$login = "";			
			
			if(isset($_POST['btn_ok']))
			{
				$login = $_POST['login'];
				$password = $_POST['password'];


				$errors = array();

				if(empty($errors))
				{
					$user_id = User::auth($login, $password); 

					if($user_id)
					{
						$_SESSION["user_id"] = $user_id;
						header("Location: /");
					}
					else
					{
						$errors['auth'] = "Неверный логин или пароль";
					}
				}
			}

			require_once(ROOT . '/views/user/login.php');
			
			return true;
		}

		/**
		* Выход из аккаунта
		*
		* Редирект на главную страницу
		*/
		public function actionLogout()
		{
			if(User::checkLogged())
            {
            	unset($_SESSION['user_id']);
            	header("Location: /");

            	return true;
            }
            return null;
		}
	}

?>