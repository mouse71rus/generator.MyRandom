<?php
	/**
	* Контроллер HomeController
	*
	* Основные разделы сайта
	*
	* @author mouse71rus
	* @version 1.0
	* @copyright Copyright (c) 2019, mouse71rus
	*/
	class HomeController 
	{
		public function actionIndex()
		{			
			require(ROOT . '/views/index.php');
			
			return true;
		}

		public function actionAjax_rand()
		{
			$errors = array();


			$seed = trim($_POST['seed']);
			$seed_auto = ($_POST['seed_auto'] == "false") ? false : true;

			if(!$seed_auto && !preg_match('/^[0-9]+$/', $seed))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Начальное значение указано в неверном формате"
				);
			}

			$a = trim($_POST['multiplier']);
			$c = trim($_POST['addend']);
			$m = trim($_POST['mask']);
			$advanced = ($_POST['advanced'] == "true") ? true : false;

			if($advanced && !preg_match('/^[0-9]+$/', $a))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Множитель а указан в неверном формате"
				);
			}
			if($advanced && !preg_match('/^[0-9]+$/', $c))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Приращение c указано в неверном формате"
				);
			}
			if($advanced && !preg_match('/^[0-9]+$/', $m))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Модуль m указан в неверном формате"
				);
			}

			$count = trim($_POST['count']);
			if(!preg_match('/^[0-9]+$/', $count))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Количество интераций указано в неверном формате"
				);
			}
			$lagA = trim($_POST['lagA']);
			if(!preg_match('/^[0-9]+$/', $lagA))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "LagA указан в неверном формате"
				);
			}
			$lagB = trim($_POST['lagB']);
			if(!preg_match('/^[0-9]+$/', $lagB))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "LagB указан в неверном формате"
				);
			}
			

			

			
			$unlimited = ($_POST['Unlimited'] == "false") ? false : true;

			$left = trim($_POST['left']);
			if(!$unlimited && !preg_match('/^[0-9]+$/', $left))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Количество интераций указано в неверном формате"
				);
			}
			$right = trim($_POST['right']);
			if(!$unlimited && !preg_match('/^[0-9]+$/', $right))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Количество интераций указано в неверном формате"
				);
			}
			
			$__part = trim($_POST['__part']);
			if(!preg_match('/^[0-9]+$/', $__part))
			{
				$errors['errors'][] = array(
					'error_type' => "errorValidate", 
					'message' => "Ошибка выполнения"
				);
			}

			$mod = trim($_POST['mod']);
			switch($mod)
			{
				case "Line":
					break;
				case "Fib":
					break;
				case "Pi":
					break;
				default:
					$errors['errors'][] = array(
						'error_type' => "errorValidate", 
						'message' => "Неверный режим выполнения"
					);
					break;
			}
			$print_mod = trim($_POST['print_mod']);
			switch($print_mod)
			{
				case "File":
					break;
				case "Screen":
					break;
				default:
					$errors['errors'][] = array(
						'error_type' => "errorValidate", 
						'message' => "Неверный режим вывода"
					);
					break;
			}


			if(empty($errors))
			{
				switch($mod)
				{
					case "Line":
						$res = intdiv($count, 100);
						$rnd = null;

						if($seed_auto)
						{
							$rnd = new myRandom();
						}
						else
						{
							$rnd = new myRandom($seed);
						}
						
						if($advanced)
						{
							$rnd->setAdvancedSetting($a, $c, $m);
						}

						$data['status'] = "ok";
						$data['data'] = [];

						if(!$unlimited)
						{
							if($__part != 100)
							{
								for ($i=$res * ($__part - 1); $i < $res * $__part; $i++) 
								{ 
									$data['data'][] = $rnd->randRange($left, $right);
								}
							}
							else
							{
								for ($i=$res * 99; $i < $count; $i++) 
								{ 
									$data['data'][] = $rnd->randRange($left, $right);				
								}
							}
						}
						else
						{
							if($__part != 100)
							{
								for ($i=$res * ($__part - 1); $i < $res * $__part; $i++) 
								{ 
									$data['data'][] = $rnd->rand();
								}
							}
							else
							{
								for ($i=$res * 99; $i < $count; $i++) 
								{ 
									$data['data'][] = $rnd->rand();				
								}
							}
						}
						

						$data['seed'] = $rnd->seed;

						echo json_encode($data);
						break;
					case "Fib":
						$res = intdiv($count, 100);
						$rnd = null;

						if($seed_auto)
						{
							$rnd = new myRandom();
						}
						else
						{
							$rnd = new myRandom($seed);
						}

						if($advanced)
						{
							$rnd->setAdvancedSetting($a, $c, $m);
						}

						$data['status'] = "ok";
						$data['data'] = [];


						if(!$unlimited)
						{
							if($__part != 100)
							{
								foreach ($rnd->FibGenerate($lagA, $lagB, $res) as $value) 
								{
									$data['data'][] = ($left) + $value % $right;
								}
							}
							else
							{
								foreach ($rnd->FibGenerate($lagA, $lagB, $res) as $value) 
								{
									$data['data'][] = ($left) + $value % $right;
								}


								$ost = $count % 100;
								$mas = $rnd->FibGenerate($lagA, $lagB, $ost);
								if(!empty($mas))
								{
									foreach ($mas as $value) {
										$data['data'][] = ($left) + $value % $right;
									}
								}
							}
						}
						else
						{
							if($__part != 100)
							{
								$data['data'] = $rnd->FibGenerate($lagA, $lagB, $res);
							}
							else
							{
								$data['data'] = $rnd->FibGenerate($lagA, $lagB, $res);

								$ost = $count % 100;
								$mas = $rnd->FibGenerate($lagA, $lagB, $ost);
								if(!empty($mas))
								{
									foreach ($mas as $value) {
										$data['data'][] = $value;
									}
								}
							}
						}


						



						$data['seed'] = $rnd->seed;

						echo json_encode($data);
						break;
					case "Pi":
						$res = intdiv($count, 100);
						$rnd = null;

						if($seed_auto)
						{
							$rnd = new myRandom();
						}
						else
						{
							$rnd = new myRandom($seed);
						}

						if($advanced)
						{
							$rnd->setAdvancedSetting($a, $c, $m);
						}

						$data['status'] = "ok";

						$data['Pi'] = $rnd->getPI($count);



						echo json_encode($data);
						break;
					default:
						break;
				}
				
			}
			else
			{
				echo json_encode($errors);
			}

			return true;
		}
	}
?>