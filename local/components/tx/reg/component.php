<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
	//echo '<pre>';print_r($arParams); echo '</pre>';
	$arParams['AUTHORIZED'] = $USER->IsAuthorized();
	
	if(!$arParams['AUTHORIZED']) {
		if($_POST['token']) {
			//получить данные с сервиса ulogin
			$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] .
			'&host=' . $_SERVER['HTTP_HOST']);
			$ulogin = json_decode($s, true);
			
			//получить логин
			$login = $ulogin['network'].$ulogin['uid'];
			
			//найти пользователя по логину
			if($user_id = $this->getUserIdByLogin($login)) {
				//авторизовать пользователя
				$USER->Authorize($user_id, true);
				
				//редирект на главную
				LocalRedirect('/');				
			}else if($user_id = $this->addUser($login, $ulogin)) {//добавить пользователя
				//авторизовать пользователя
				$USER->Authorize($user_id, true);
				
				//редирект на главную
				LocalRedirect('/');				
			}
		}
		$this->IncludeComponentTemplate();//вывести тему
	}else {
		//перейти на главную страницу
		LocalRedirect('/');	
	}
?>
